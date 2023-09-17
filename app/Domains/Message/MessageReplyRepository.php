<?php

namespace App\Domains\Message;

use App\Models\Message;
use App\OpenAi\Dtos\MessageDto;
use App\OpenAi\Dtos\MessagesDto;
use Facades\App\OpenAi\ChatClient;

class MessageReplyRepository extends MessageRepository
{

    protected Message $child_message;


    public function handle(Message $message): Message
    {
        $this->parent_message = $message->parent;
        $this->child_message = $message;

        $prompts = $this->createPrompt();

        $prompt = MessageDto::from([
            'role' => 'user',
            'content' => $this->child_message->content,
        ]);

        $prompts->messages[] = $prompt;

        $this->messageBuilder->setMessages($prompts);

        return ChatClient::setMessage($message)
            ->chat(messages: $this->messageBuilder->getMessagesLimitTokenCount(remove_token_count: true));
    }

    protected function createPrompt(): MessagesDto
    {
        $prompts = [];

        $prompts[] = MessageDto::from([
            'role' => 'system',
            'content' => $this->systemPrompt(),
        ]);

        $prompts[] = MessageDto::from([
            'role' => 'user',
            'content' => $this->parent_message->content,
        ]);

        $messages = Message::query()
            ->where('parent_id', $this->parent_message->id)
            ->where("id", "!=", $this->child_message->id)
            ->oldest()
            ->limit(5)
            ->get();

        $prompts = $this->iterateToMakePrompts($prompts, $messages);

        return MessagesDto::from([
            'messages' => $prompts,
        ]);
    }
}

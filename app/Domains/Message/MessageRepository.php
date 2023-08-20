<?php

namespace App\Domains\Message;

use App\Models\Message;
use App\OpenAi\Dtos\MessageDto;
use App\OpenAi\Dtos\MessagesDto;
use Facades\App\OpenAi\ChatClient;
use App\OpenAi\Dtos\Response;
use App\OpenAi\MessageBuilder;

class MessageRepository
{
    protected MessageBuilder $messageBuilder;

    protected Message $parent_message;

    public function __construct(MessageBuilder $messageBuilder = null)
    {
        if (! $messageBuilder) {
            $messageBuilder = new MessageBuilder();
        }

        $this->messageBuilder = $messageBuilder;
    }

    public function handle(Message $message):Response {
        $this->parent_message = $message;

        $this->messageBuilder->setMessages($this->createPrompt());

        return ChatClient::chat($this->messageBuilder->getMessagesLimitTokenCount(
            remove_token_count: true
        ));
    }

    protected function createPrompt() : MessagesDto {
        $prompts = [];

        $prompts[] = MessageDto::from([
            'role' => 'system',
            'content' => $this->systemPrompt(),
        ]);

        $messages = Message::query()
            ->where("parent_id", $this->parent_message->id)->latest()
            ->limit(3)
            ->get();

        foreach($messages as $message) {
            $prompts[] = MessageDto::from([
                'role' => $message->role,
                'content' => $message->content,
            ]);
        }

        return MessagesDto::from([
            'messages' => $prompts,
        ]);
    }

    /**
     * @TODO
     * MetaData here and or in the user prompt
     *
     */
    protected function systemPrompt() : string
    {
        if(!empty($this->parent_message->meta_data)) {
            $content =
                'Acting as the users assistant and using the following meta data included in the question please answer their question:';
            $content = str($content);
            foreach($this->parent_message->meta_data as $meta_data) {
                $content = $content->append(sprintf("%s: %s", $meta_data->label, $meta_data->content));
            }

            return $content->append("### End Meta Data ### \n\n\n")->toString();
        }
        return 'Acting as the users assistant please answer their question';
    }
}

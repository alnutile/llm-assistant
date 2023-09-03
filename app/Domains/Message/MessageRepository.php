<?php

namespace App\Domains\Message;

use App\Domains\LlmFunctions\Dto\RoleTypeEnum;
use App\Models\Message;
use App\OpenAi\Dtos\MessageDto;
use App\OpenAi\Dtos\MessagesDto;
use App\OpenAi\MessageBuilder;
use Facades\App\OpenAi\ChatClient;

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

    public function handle(Message $message): Message
    {
        $this->parent_message = $message;

        $prompts = $this->createPrompt();

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

        logger('Latest id '.$this->parent_message->id);

        $messages = Message::query()
            ->where('parent_id', $this->parent_message->id)->latest()
            ->limit(5)
            ->get();

        /** @var Message $message */
        foreach ($messages as $message) {
            /**
             * @see https://openai.com/blog/function-calling-and-other-api-updates
             */
            if ($message->role === RoleTypeEnum::Function) {
                $prompts[] = MessageDto::from([
                    'role' => $message->role->value,
                    'content' => $message->content,
                    'name' => $message->name,
                ]);
            } elseif ($this->callWasResultOfFunctionCall($message)) {
                $prompts[] = MessageDto::from([
                    'role' => RoleTypeEnum::Assistant,
                    'content' => $message->function_call->toJson(),
                ]);
            } else {
                $prompts[] = MessageDto::from([
                    'role' => $message->role->value,
                    'content' => $message->content,
                ]);
            }
        }

        put_fixture('prompts_before.json', $prompts);

        return MessagesDto::from([
            'messages' => $prompts,
        ]);
    }

    /**
     * @TODO
     * MetaData here and or in the user prompt
     */
    protected function systemPrompt(): string
    {
        if (! empty($this->parent_message->meta_data)) {
            $content =
                sprintf('Acting as the users assistant and using the following meta data included in the question please answer their question,
                Current Date is %s
                Only use the functions you have been provided with if needed to help the user with this question:', now()->format('YYYY-d-m h:m'));
            $content = str($content);
            foreach ($this->parent_message->meta_data as $meta_data) {
                $content = $content->append(sprintf('%s: %s', $meta_data->label, $meta_data->content));
            }

            return $content->append("### End Meta Data ### \n\n\n")->toString();
        }

        return 'Acting as the users assistant please answer their question';
    }

    protected function callWasResultOfFunctionCall(\Illuminate\Database\Eloquent\Builder|Message $message): bool
    {
        return $message->role === RoleTypeEnum::Assistant && $message->content === null && $message->function_call !== null;
    }
}

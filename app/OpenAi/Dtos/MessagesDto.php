<?php

namespace App\OpenAi\Dtos;

class MessagesDto extends \Spatie\LaravelData\Data
{
    /**
     * @param  MessageDto[]  $messages
     */
    public function __construct(
        public array $messages
    ) {
    }

    public static function createFromArray(array $messages): MessagesDto
    {
        $messagesDto = [];
        foreach ($messages as $message) {
            $messagesDto[] = MessageDto::from([
                'role' => data_get($message, 'role'),
                'content' => data_get($message, 'content'),
                'function' => data_get($message, 'function'),
            ]);
        }

        return MessagesDto::from(['messages' => $messagesDto]);
    }
}

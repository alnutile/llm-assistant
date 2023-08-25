<?php

namespace App\Console\Commands;

use App\Models\Message;
use Facades\App\OpenAi\ChatClient;
use Illuminate\Console\Command;

class ExampleChatWithFunction extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'llmassistant:example_chat_with_functions {message}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'See the functions working';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $messages = [];

        $messages[] = [
            'role' => 'system',
            'content' => 'Only use the functions you have been provided with if needed to help the user with this question. As a helpful assistant please assist the user in marketing and staying on track for this question/idea',
        ];

        $message = Message::find($this->argument('message'));
        $messages[] = [
            'role' => 'user',
            'content' => $message->content,
        ];

        $this->info('Sending request');

        $results = ChatClient::setMessage($message)->chat($messages);

        $this->info($results->content);
    }
}

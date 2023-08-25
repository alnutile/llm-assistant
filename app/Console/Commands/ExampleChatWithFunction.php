<?php

namespace App\Console\Commands;

use Facades\App\OpenAi\ChatClient;
use Illuminate\Console\Command;

class ExampleChatWithFunction extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'llmassistant:example_chat_with_functions';

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
        $question = "I need help marketing an idea about an LLM Assistant. To start I just need you to make a schedule of a few days in this next week to make sure I do some tasks to stay on top of it.
        Can you suggest some tasks like 'Post to LinkedIn to Market the idea', 'Code and Planning time', 'YouTube video due' so I end up with a plan that will help me market it and build it
        ";
        $messages = [];
        $messages[] = [
            'role' => 'system',
            'content' => 'Only use the functions you have been provided with if needed to help the user with this question. As a helpful assistant please assist the user in marketing and staying on track for this question/idea',
        ];
        $messages[] = [
            'role' => 'user',
            'content' => $question,
        ];

        $this->info('Sending request');

        $results = ChatClient::chat($messages, ['llm_functions_scheduling']);

        $this->info($results->content);
    }
}

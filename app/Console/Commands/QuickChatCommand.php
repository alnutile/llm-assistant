<?php

namespace App\Console\Commands;

use Facades\App\OpenAi\ChatClient;
use Illuminate\Console\Command;

class QuickChatCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'llm_assistant:quick_chat {question}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send a quick question';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $question = $this->argument('question');
        $messages = [];
        $messages[] = [
            'role' => 'system',
            'content' => 'As a helfpul assitant please answer the question',
        ];
        $messages[] = [
            'role' => 'user',
            'content' => $question,
        ];

        $this->info('Sending request');

        $results = ChatClient::chat($messages, true);

        $this->info($results->content);
    }
}

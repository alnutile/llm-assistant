<?php

namespace App\Console\Commands;

use App\Domains\LlmFunctions\Dto\RoleTypeEnum;
use App\Models\Message;
use App\Models\User;
use Facades\App\OpenAi\ChatClient;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Auth;

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
        $user = User::first();
        auth()->login($user);
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


        $message = Message::create([
            'role' => RoleTypeEnum::User,
            'content' => $question,
            'user_id' => $user->id
        ]);

        $this->info('Sending request');

        $results = ChatClient::setMessage($message)->chat($messages);

        $this->info($results->content);
    }
}

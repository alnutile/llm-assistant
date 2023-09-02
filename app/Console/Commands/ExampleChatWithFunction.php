<?php

namespace App\Console\Commands;

use App\Domains\LlmFunctions\Dto\RoleTypeEnum;
use App\Models\LlmFunction;
use App\Models\Message;
use App\Models\User;
use Facades\App\OpenAi\ChatClient;
use Illuminate\Console\Command;

class ExampleChatWithFunction extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'llmassistant:get_content_from_url {url}';

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
        $user = User::first();
        auth()->login($user);
        $question = $this->argument('url');
        $question = "Get content for following url " . $question;
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
        $function = LlmFunction::label('get_content_from_url')->firstOrFail();
        $message->llm_functions()->attach([$function->id]);

        $this->info('Sending request');

        $results = ChatClient::setMessage($message)
            ->chat($messages);

        $this->info("Message ID: " . $message->id);
        $this->info($results->content);
    }
}

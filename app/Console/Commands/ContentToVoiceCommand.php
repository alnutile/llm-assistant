<?php

namespace App\Console\Commands;

use Facades\App\Domains\LlmFunctions\ContentToVoice\ContentToVoiceClient;
use Illuminate\Console\Command;

class ContentToVoiceCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'llmassistant:content-to-voice-command {text}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Pass a small amount of text to see it converted';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $text = $this->argument('text');
        $this->info('Going to convert '.$text);
        $this->info('Keep an eye on the logs');
        $results = ContentToVoiceClient::handle($text);
        $this->info('Url: '.$results);
    }
}

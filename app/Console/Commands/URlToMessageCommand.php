<?php

namespace App\Console\Commands;

use App\Domains\EmailParser\MailDto;
use App\Jobs\MailBoxParserJob;
use Illuminate\Console\Command;

class URlToMessageCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'llmassistant:url_to_message {url}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Give a url and the system will get the content';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $url = $this->argument('url');
        $this->info('Getting content for url '.$url.' check logs');
        $dto = MailDto::from([
            'subject' => 'Get content for the url',
            'body' => ' This is the url to get copy for '.$url,
        ]);
        $job = new MailBoxParserJob($dto);
        $job->handle();
        $this->info('Check the queue!');
    }
}

<?php

namespace App\Jobs;

use App\Domains\EmailParser\MailDto;
use App\Models\LlmFunction;
use App\Models\Message;
use App\Models\Tag;
use App\Models\User;
use Facades\App\OpenAi\ChatClient;
use Facades\App\Tools\GetSiteWrapper;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use SundanceSolutions\LarachainTrimText\Facades\LarachainTrimText;

class MailBoxParserJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct(public MailDto $mailDto)
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        try {

            $tag1 = Tag::firstOrCreate([
                'label' => 'email',
            ], [
                'active' => 1,
            ]);

            $tag2 = Tag::firstOrCreate([
                'label' => 'tldr',
            ], [
                'active' => 1,
            ]);

            /**
             * @TODO
             * The emails can have triggers like #tldr or #get_content
             * Since this is my most common use case I will just assume this
             */
            $function = LlmFunction::whereLabel('get_content_from_url')->first();

            $content = $this->mailDto->body;

            /**
             * @TODO strip signatures
             * This will pick up signature :(
             */
            if($url = get_url_from_body($content)) {
                $content = str($content)->prepend("get content from the url {$url} using the included function")->toString();
            }

            $message = Message::create([
                'role' => 'user',
                'user_id' => User::first()->id,
                'content' => sprintf("subject: %s \n body: %s",
                    $this->mailDto->subject,
                    $content),
            ]);

            $message->tags()->attach([
                $tag1->id, $tag2->id,
            ]);

            $message->llm_functions()->attach([
                $function->id
            ]);

            MessageCreatedJob::dispatch($message);

        } catch (\Exception $e) {
            logger('Email error', [$e->getMessage()]);

            throw $e;
        }
    }


}

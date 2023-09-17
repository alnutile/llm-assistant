<?php

namespace App\Jobs;

use App\Domains\EmailParser\MailDto;
use App\Models\LlmFunction;
use App\Models\Message;
use App\Models\Tag;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

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

            $content = $this->mailDto->body;

            /**
             * @TODO strip signatures
             * This will pick up signature :(
             */
            if ($url = get_url_from_body($content)) {
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

            /**
             * @NOTE
             * These are the functions I want to use
             */
            $functions = ['get_content_from_url', 'add_tags_to_article', 'get_existing_tags'];

            foreach ($functions as $function) {
                $functionModel = LlmFunction::whereLabel('get_content_from_url')->first();

                $message->llm_functions()->attach([
                    $functionModel->id,
                ]);
            }

            MessageCreatedJob::dispatch($message);

        } catch (\Exception $e) {
            logger('Email error', [$e->getMessage()]);

            throw $e;
        }
    }
}

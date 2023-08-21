<?php

namespace App\Jobs;

use App\Domains\EmailParser\MailDto;
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

            $tag = Tag::firstOrCreate([
                'label' => 'email',
            ], [
                'active' => 1,
            ]);

            $message = Message::create([
                'role' => 'user',
                'user_id' => User::first()->id,
                'content' => sprintf("subject: %s \n body: %s",
                    $this->mailDto->subject,
                    $this->mailDto->body),
            ]);

            $message->tags()->attach($tag->id);

            /**
             * If tons of text we tldr it
             * If a url we go get it then tldr it
             * can make that a function?
             */
        } catch (\Exception $e) {
            logger('Email error', [$e->getMessage()]);

            throw $e;
        }
    }
}

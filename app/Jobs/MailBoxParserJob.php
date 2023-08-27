<?php

namespace App\Jobs;

use App\Domains\EmailParser\MailDto;
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

            $content = $this->mailDto->body;

            $content = $this->seeIfHasUrl($content);

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
             * If tons of text we tldr it
             * If a url we go get it then tldr it
             * can make that a function?
             */
        } catch (\Exception $e) {
            logger('Email error', [$e->getMessage()]);

            throw $e;
        }
    }

    private function seeIfHasUrl(?string $content):?string
    {
        $hasUrl = get_url_from_body($content);

        if ($hasUrl) {

            $body = GetSiteWrapper::handle($hasUrl);
            if ($body > config('openai.max_question_size')) {
                $content = LarachainTrimText::trim($body);
            } else {
                $content = $body;
            }

            $messages = [];
            $messages[] = [
                'role' => 'system',
                'content' => 'This is HTML of a site I just got a page from can you clean it up so it is just the main content of the site for reading',
            ];
            $messages[] = [
                'role' => 'user',
                'content' => $content,
            ];

            $results = ChatClient::chat($messages);

            $content = $results->content;

            $content = format_text_for_message($content);

            $content = sprintf("
                URL: %s\n
                Content: %s",
                $hasUrl,
                $content
            );
        }
        return $content;
    }
}

<?php

namespace App\Jobs;

use App\Events\MessageStatusEvent;
use App\Models\Message;
use App\OpenAi\Dtos\Response;
use Facades\App\Domains\Message\MessageReplyRepository;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class ReplyMessageCreateJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct(public Message $message)
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        try {
            MessageStatusEvent::dispatch($this->message);
            /** @var Response $results */
            $results = MessageReplyRepository::handle($this->message);
            Message::create([
                'user_id' => $this->message->user_id,
                'content' => $results->content,
                'role' => 'assistant',
                'parent_id' => $this->message->parent_id,
            ]);
            MessageStatusEvent::dispatch($this->message);
        } catch (\Exception $e) {
            logger('Error getting results');
            logger($e->getMessage());

            throw $e;
        }
    }
}

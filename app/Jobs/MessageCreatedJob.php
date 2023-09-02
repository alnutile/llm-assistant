<?php

namespace App\Jobs;

use App\Events\MessageStatusEvent;
use App\Models\Message;
use Facades\App\Domains\Message\MessageRepository;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class MessageCreatedJob implements ShouldQueue
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
            logger('Sending llm request');
            MessageStatusEvent::dispatch($this->message);
            MessageRepository::handle($this->message);
            MessageStatusEvent::dispatch($this->message);
        } catch (\Exception $e) {
            logger('Error getting results');
            logger($e->getMessage());
            throw $e;
        }
    }
}

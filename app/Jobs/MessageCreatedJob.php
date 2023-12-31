<?php

namespace App\Jobs;

use App\Domains\LlmFunctions\Dto\RoleTypeEnum;
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
            /** @var ?Message $message */
            $message = MessageRepository::handle($this->message);

            if ($message === null) {
                MessageStatusEvent::dispatch($this->message);

                return;
            }

            if ($message->role === RoleTypeEnum::Function) {
                MessageCreatedJob::dispatch($this->message);
            }

            MessageStatusEvent::dispatch($this->message);
        } catch (\Exception $e) {
            logger('Error getting results');
            logger($e->getMessage());
            throw $e;
        }
    }
}

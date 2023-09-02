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
            /** @var Message $message */
            $message = MessageRepository::handle($this->message);

            logger("Message role and function", [
               'role' => $message->role->name,
               'function' => $message->function_call
            ]);

            /**
             * @NOTE
             * Do a follow up since it was a function
             * so we can get
             */
            if($message->role === RoleTypeEnum::Function) {
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

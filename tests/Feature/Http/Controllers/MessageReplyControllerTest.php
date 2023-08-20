<?php

namespace Tests\Feature\Http\Controllers;

use App\Jobs\ReplyMessageCreateJob;
use App\Models\Message;
use App\Models\User;
use Illuminate\Support\Facades\Queue;
use Tests\TestCase;

class MessageReplyControllerTest extends TestCase
{
    public function test_reply(): void
    {
        Queue::fake();
        $user = User::factory()->create();
        $parent = Message::factory()->create();
        $message = Message::factory()->create([
            'parent_id' => $parent->id,
        ]);
        $this->actingAs($user)->put(route('message_reply.reply', [
            'message' => $message->id,
        ]), [
            'content' => 'Foo bar',
        ]);
        Queue::assertPushed(ReplyMessageCreateJob::class);
    }
}

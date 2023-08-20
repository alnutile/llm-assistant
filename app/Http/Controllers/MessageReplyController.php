<?php

namespace App\Http\Controllers;

use App\Jobs\ReplyMessageCreateJob;
use App\Models\Message;

class MessageReplyController extends Controller
{
    public function reply(Message $message)
    {
        $validated = request()->validate([
            'content' => ['required'],
        ]);

        Message::create([
            'content' => $validated['content'],
            'role' => 'user',
            'parent_id' => $message->id,
            'user_id' => auth()->user()->id,
        ]);

        /**
         * @NOTE we always start with the parent
         */
        if ($message->parent_id) {
            $message = $message->parent;
        }

        ReplyMessageCreateJob::dispatch($message);

        request()->session()->flash('flash.banner', 'Reply Sent to LLM 🤖');

        return back();

    }
}

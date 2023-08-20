<?php

namespace App\Http\Controllers;

use App\Http\Resources\MessageResource;
use App\Models\Message;
use Illuminate\Http\Request;

class MessageController extends Controller
{

    public function create() {
        return inertia("Messages/Create");
    }

    public function store() {
        $validated = request()->validate([
            'message' => ['required']
        ]);

        $message = Message::create([
            'content' => $validated['message'],
            'role' => 'user',
            'user_id' => auth()->user()->id,
        ]);

        request()->session()->flash('flash.banner', 'Thread started');

        return to_route('messages.show', [
            'message' => $message->id
        ]);
    }

    public function show(Message $message) {
        return inertia("Messages/Show", [
           'message' => new MessageResource($message)
        ]);
    }
}

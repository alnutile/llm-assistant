<?php

namespace App\Http\Controllers;

use App\Http\Resources\MessageResource;
use App\Models\Message;
use App\Models\MetaData;

class MessageController extends Controller
{
    public function create()
    {
        return inertia('Messages/Create', [
            'meta_data' => MetaData::query()->where('user_id', auth()->user()->id)->get(),
        ]);
    }

    public function store()
    {
        $validated = request()->validate([
            'message' => ['required'],
            'meta_data' => ['nullable'],
        ]);

        $meta_data = data_get($validated, 'meta_data', []);
        unset($validated['meta_data']);

        $message = Message::create([
            'content' => $validated['message'],
            'role' => 'user',
            'user_id' => auth()->user()->id,
        ]);

        $message->meta_data()->attach(
            collect($meta_data)->pluck('id')->values()
        );

        request()->session()->flash('flash.banner', 'Thread started');

        return to_route('messages.show', [
            'message' => $message->id,
        ]);
    }

    public function show(Message $message)
    {
        return inertia('Messages/Show', [
            'message' => new MessageResource($message),
        ]);
    }
}

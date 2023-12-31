<?php

namespace App\Http\Controllers;

use App\Http\Resources\MessageResource;
use App\Jobs\MessageCreatedJob;
use App\Models\LlmFunction;
use App\Models\Message;
use App\Models\MetaData;
use App\Models\Tag;

class MessageController extends Controller
{
    public function create()
    {
        return inertia('Messages/Create', [
            'meta_data' => MetaData::query()->where('user_id', auth()->user()->id)->get(),
            'tags' => Tag::orderBy('label')->get(),
            'llm_functions' => LlmFunction::orderBy('label')->get(),
        ]);
    }

    public function store()
    {
        $validated = request()->validate([
            'content' => ['required'],
            'meta_data' => ['nullable'],
            'tags' => ['nullable'],
            'llm_functions' => ['nullable'],
        ]);

        $meta_data = data_get($validated, 'meta_data', []);
        unset($validated['meta_data']);

        $tags = data_get($validated, 'tags', []);
        unset($validated['tags']);

        $llm_functions = data_get($validated, 'llm_functions', []);
        unset($validated['llm_functions']);

        $message = Message::create([
            'content' => $validated['content'],
            'role' => 'user',
            'user_id' => auth()->user()->id,
        ]);

        $message->meta_data()->attach(
            collect($meta_data)->pluck('id')->values()
        );

        $message->llm_functions()->attach(
            collect($llm_functions)->pluck('id')->values()
        );

        $message->tags()->attach(
            collect($tags)->pluck('id')->values()
        );

        MessageCreatedJob::dispatch($message);

        request()->session()->flash('flash.banner', 'Thread started');

        return to_route('messages.show', [
            'message' => $message->id,
        ]);
    }

    public function rerunLlm(Message $message)
    {
        $message->children()->delete();

        MessageCreatedJob::dispatch($message);

        request()->session()->flash('flash.banner', 'Re-running LLM 🏃‍');

        return back();
    }

    public function update(Message $message)
    {
        $validated = request()->validate([
            'content' => ['required'],
            'meta_data' => ['nullable'],
            'tags' => ['nullable'],
        ]);

        $meta_data = data_get($validated, 'meta_data', []);
        unset($validated['meta_data']);

        $tags = data_get($validated, 'tags', []);
        unset($validated['tags']);

        $message->update($validated);

        $message->meta_data()->sync(
            collect($meta_data)->pluck('id')->values()
        );

        $message->tags()->sync(
            collect($tags)->pluck('id')->values()
        );

        $message->children()->delete();

        MessageCreatedJob::dispatch($message);

        request()->session()->flash('flash.banner', 'Thread started');

        return to_route('messages.show', [
            'message' => $message->id,
        ]);
    }

    public function edit(Message $message)
    {
        return inertia('Messages/Edit', [
            'message' => new MessageResource($message),
            'tags' => Tag::orderBy('label')->get(),
            'meta_data' => MetaData::query()->where('user_id', auth()->user()->id)->get(),
        ]);
    }

    public function show(Message $message)
    {
        return inertia('Messages/Show', [
            'message' => new MessageResource($message),
        ]);
    }

    public function delete(Message $message)
    {
        $message->delete();
        request()->session()->flash('flash.banner', 'Deleted Message');

        return to_route('dashboard');
    }
}

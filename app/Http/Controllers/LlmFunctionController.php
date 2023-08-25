<?php

namespace App\Http\Controllers;

use App\Http\Resources\LlmFunctionResource;
use App\Models\LlmFunction;

class LlmFunctionController extends Controller
{
    public function index()
    {
        return inertia('LlmFunctions/Index', [
            'llm_functions' => LlmFunctionResource::collection(LlmFunction::orderBy('label')->get()),
        ]);
    }

    public function create()
    {
        return inertia('LlmFunctions/Create');
    }

    public function edit(LlmFunction $llmFunction)
    {
        return inertia('LlmFunctions/Edit', [
            'llm_function' => new LlmFunctionResource($llmFunction),
        ]);
    }

    public function store()
    {
        $validated = request()->validate([
            'label' => ['required'],
            'description' => ['required'],
            'parameters' => ['required'],
        ]);

        LlmFunction::create($validated);

        request()->session()->flash('flash.banner', 'Created 🏄');

        return to_route('llm_functions.index');
    }

    public function update(LlmFunction $llmFunction)
    {
        $validated = request()->validate([
            'label' => ['required'],
            'description' => ['required'],
            'parameters' => ['required'],
        ]);

        $llmFunction->update($validated);

        request()->session()->flash('flash.banner', 'Updated 🌮');

        return to_route('llm_functions.index');
    }
}

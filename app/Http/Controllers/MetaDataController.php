<?php

namespace App\Http\Controllers;

use App\Http\Resources\MetaDataResource;
use App\Models\MetaData;

class MetaDataController extends Controller
{
    public function index()
    {
        return inertia('MetaData/Index', [
            'meta_data' => MetaDataResource::collection(auth()->user()->meta_data),
        ]);
    }

    public function store()
    {
        $validated = request()->validate([
            'label' => ['required'],
            'content' => ['required'],
        ]);

        $validated['user_id'] = auth()->user()->id;

        MetaData::create($validated);

        request()->session()->flash('flash.banner', 'Created 🏄');

        return to_route('meta_data.index');
    }

    public function edit(MetaData $meta_data)
    {
        return inertia('MetaData/Edit', [
            'meta_data' => new MetaDataResource($meta_data),
        ]);
    }

    public function update(MetaData $meta_data)
    {
        $validated = request()->validate([
            'label' => ['required'],
            'content' => ['required'],
        ]);

        $validated['user_id'] = auth()->user()->id;

        $meta_data->update($validated);

        request()->session()->flash('flash.banner', 'Updated 🌮');

        return to_route('meta_data.index');
    }

    public function create()
    {
        return inertia('MetaData/Create');
    }
}

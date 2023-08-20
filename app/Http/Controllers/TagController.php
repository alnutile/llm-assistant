<?php

namespace App\Http\Controllers;

use App\Models\Tag;

class TagController extends Controller
{
    public function store()
    {
        $validated = request()->validate([
            'label' => ['required'],
        ]);

        Tag::create([
            'label' => $validated['label'],
        ]);

        request()->session()->flash('flash.banner', 'Tag Created');

        return back();
    }
}

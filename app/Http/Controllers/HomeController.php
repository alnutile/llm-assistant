<?php

namespace App\Http\Controllers;

use App\Http\Resources\MessageResource;
use App\Models\Message;

class HomeController extends Controller
{
    public function __invoke()
    {
        return inertia('Dashboard/Show', [
            'messages' => MessageResource::collection(Message::query()
                ->whereNull("parent_id")
                ->where('user_id', auth()->user()->id)
                ->latest()
                ->get()),
        ]);
    }
}

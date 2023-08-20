<?php

namespace App\Http\Controllers;

use App\Http\Resources\MessageResource;
use App\Http\Resources\TagResource;
use App\Models\Message;
use App\Models\Tag;
use Illuminate\Database\Eloquent\Builder;

class HomeController extends Controller
{
    public function __invoke()
    {
        $tags = request()->get('tags');

        $filters = [
            'tags' => (! empty($tags)) ? Tag::whereIn('id', $tags)->get() : [],
        ];

        return inertia('Dashboard/Show', [
            'filters' => $filters,
            'tags' => TagResource::collection(Tag::get()),
            'messages' => MessageResource::collection(Message::query()
                ->when($tags, function (Builder $query) use ($tags) {
                    return $query->whereHas('tags', function (Builder $query) use ($tags) {
                        return $query->whereIn('tags.id', $tags);
                    });
                })
                ->whereNull('parent_id')
                ->where('user_id', auth()->user()->id)
                ->latest()
                ->get()),
        ]);
    }
}

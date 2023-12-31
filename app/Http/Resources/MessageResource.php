<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class MessageResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'content' => $this->content,
            'content_formatted' => $this->message_formatted,
            'content_truncated' => str($this->message_formatted)->limit(200)->toString(),
            'user' => $this->user,
            'role' => $this->role,
            'meta_data' => MetaDataResource::collection($this->meta_data),
            'tags' => TagResource::collection($this->tags),
            'llm_functions' => LlmFunctionResource::collection($this->llm_functions),
            'children' => MessageResource::collection($this->children()->whereNotNull('content')
                ->oldest()->get()
            ),
            'created_at' => $this->created_at->diffForHumans(),
            'created_at_formatted' => $this->created_at->diffForHumans(),
            'tasks' => TaskResource::collection($this->tasks),
        ];
    }
}

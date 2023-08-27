<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class MessageIndexResource extends JsonResource
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
            'content' => str($this->content)->limit(350),
            'content_formatted' => str($this->message_formatted)->limit(350),
            'user' => $this->user,
            'role' => $this->role,
            'meta_data' => MetaDataResource::collection($this->meta_data),
            'tags' => TagResource::collection($this->tags),
            'llm_functions' => LlmFunctionResource::collection($this->llm_functions),
            'children' => MessageResource::collection($this->children),
            'created_at' => $this->created_at->diffForHumans(),
            'created_at_formatted' => $this->created_at->diffForHumans(),
            'tasks' => TaskResource::collection($this->tasks),
        ];
    }
}

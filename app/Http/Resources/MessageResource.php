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
            'user' => $this->user,
            'meta_data' => MetaDataResource::collection($this->meta_data),
            'created_at' => $this->created_at->diffForHumans(),
            'created_at_formatted' => $this->created_at->diffForHumans(),
        ];
    }
}

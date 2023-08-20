<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\Pivot;

class MessageTag extends Pivot
{
    protected $guarded = [];

    public $timestamps = false;

    public function message(): BelongsTo
    {
        return $this->belongsTo(Message::class);
    }

    public function tag(): BelongsTo
    {
        return $this->belongsTo(Tag::class);
    }
}

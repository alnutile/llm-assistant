<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\Pivot;

class LmmFunctionMessage extends Pivot
{
    public $timestamps = false;

    public function message(): BelongsTo
    {
        return $this->belongsTo(Message::class);
    }

    public function llm_function(): BelongsTo
    {
        return $this->belongsTo(LlmFunction::class);
    }
}

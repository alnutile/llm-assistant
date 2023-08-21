<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

/**
 * @property string $label
 * @property string $content
 * @property bool $active
 */
class LlmFunction extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function messages(): BelongsToMany
    {
        return $this->belongsToMany(Message::class);
    }
}

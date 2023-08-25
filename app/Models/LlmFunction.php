<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

/**
 * @property string $label
 * @property string $description
 * @property array $parameters
 * @property bool $active
 */
class LlmFunction extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $casts = [
        'parameters' => 'array'
    ];

    public function messages(): BelongsToMany
    {
        return $this->belongsToMany(Message::class);
    }
}

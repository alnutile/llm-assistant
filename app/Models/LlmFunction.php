<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

/**
 * @property string $label
 * @property string $description
 * @property string $parameters_formatted
 * @property array $parameters
 * @property bool $active
 */
class LlmFunction extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $casts = [
        'parameters' => 'array',
    ];

    protected $appends = [
        'parameters_formatted',
    ];

    public function getParametersFormattedAttribute(): string
    {
        return json_encode($this->parameters, JSON_PRETTY_PRINT);
    }

    public function getParametersDecodedAttribute(): array
    {
        return (array) json_decode($this->parameters, true);
    }

    public function messages(): BelongsToMany
    {
        return $this->belongsToMany(Message::class);
    }
}

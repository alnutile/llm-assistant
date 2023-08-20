<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Pgvector\Laravel\Vector;

/**
 * @property int $id
 * @property string $role
 * @property int $parent_id
 * @property int $user_id
 * @property string $content
 * @property array $embedding
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon $updated_at
 * @property-read \App\Models\User $user
 * @property-read \App\Models\Message $parent
 */
class Message extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $casts = [
        'embedding' => Vector::class,
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function parent()
    {
        return $this->belongsTo(Message::class, 'parent_id');
    }

    public function children(): HasMany
    {
        return $this->hasMany(Message::class, 'parent_id');
    }
}
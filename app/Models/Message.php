<?php

namespace App\Models;

use App\Domains\LlmFunctions\Dto\FunctionCallDto;
use App\Domains\LlmFunctions\Dto\RoleTypeEnum;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Pgvector\Laravel\Vector;

/**
 * @property int $id
 * @property RoleTypeEnum $role
 * @property int $parent_id
 * @property int $user_id
 * @property string $content
 * @property string|null $name
 * @property array $embedding
 * @property FunctionCallDto|null $function_call
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon $updated_at
 * @property-read \App\Models\User $user
 * @property-read \App\Models\Message $parent
 * @property-read Collection $llm_functions
 */
class Message extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $casts = [
        'embedding' => Vector::class,
        'function_call' => FunctionCallDto::class,
        'role' => RoleTypeEnum::class,
    ];

    protected $appends = [
        'message_formatted',
    ];

    public function getMessageFormattedAttribute()
    {
        return str($this->content)->toString();
    }

    public function user(): BelongsTo
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

    public function meta_data(): BelongsToMany
    {
        return $this->belongsToMany(MetaData::class);
    }

    public function tags(): BelongsToMany
    {
        return $this->belongsToMany(Tag::class);
    }

    public function llm_functions(): BelongsToMany
    {
        return $this->belongsToMany(LlmFunction::class);
    }

    public function tasks(): HasMany
    {
        return $this->hasMany(Task::class);
    }
}

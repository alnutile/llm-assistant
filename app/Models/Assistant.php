<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Database\Eloquent\Relations\MorphTo;

/**
 * @property int $created_by_id
 * @property int $assistantable_id
 * @property string $assistantable_type
 * @property string $external_thread_id
 * @property string $external_assistant_id
 * @property User $createdBy
 */
class Assistant extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function assistantable(): MorphTo
    {
        return $this->morphTo();
    }

    public function created_by(): HasOne
    {
        return $this->hasOne(User::class, 'id', 'created_by_id');
    }
}

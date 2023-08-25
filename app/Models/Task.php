<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @property boolean $complete
 * @property Message $message
 * @property int $message_id
 * @property Carbon|null $due
 * @property Carbon|null $deleted_at
 */
class Task extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $guarded = [];

    protected $casts = ['due' => "date" ];

    public function message(): BelongsTo
    {
        return $this->belongsTo(Message::class);
    }
}

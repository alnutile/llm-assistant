<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property string $label
 * @property string $description
 * @property bool $active
 */
class LlmFunction extends Model
{
    use HasFactory;

    protected $guarded = [];
}

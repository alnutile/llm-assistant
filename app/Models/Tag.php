<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property string $label
 * @property bool $active
 */
class Tag extends Model
{
    use HasFactory;

    protected $guarded = [];
}

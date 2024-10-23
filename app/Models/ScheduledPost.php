<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ScheduledPost extends Model
{
    use HasFactory;

    // Specify the fillable attributes
    protected $fillable = [
        'content',
        'file_path',
        'link',
        'scheduled_time',
        'platform',
        'is_posted',
    ];
}

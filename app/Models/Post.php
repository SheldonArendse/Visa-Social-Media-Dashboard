<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    // Mass assigned fields
    protected $fillable = [
        'title',
        'content',
        'image_url',
        'article_url',
        'user_id',
        'published_at',
    ];

    // Define the relationship between Post and User
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}

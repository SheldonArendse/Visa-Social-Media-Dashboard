<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SocialMediaIntegration extends Model
{
    protected $fillable = [
        'user_id',
        'platform_name',
        'access_token',
        'refresh_token',
        'account_id',
        'token_expires_at',
    ];

    // Assigning social media integration to a user
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}

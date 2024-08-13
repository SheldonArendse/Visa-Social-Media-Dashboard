<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('social_media_integrations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');  // Foreign key to users table
            $table->string('platform_name');  // Social media platform
            $table->string('access_token');  // Access token for the API
            $table->string('refresh_token')->nullable();
            $table->string('account_id')->nullable();
            $table->timestamp('token_expires_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('social_media_integrations');
    }
};

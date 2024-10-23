<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateScheduledPostsTable extends Migration
{
    public function up()
    {
        Schema::create('scheduled_posts', function (Blueprint $table) {
            $table->id();
            $table->string('content'); // Post content
            $table->string('file_path')->nullable(); // Path to the uploaded media
            $table->string('link')->nullable(); // Optional link
            $table->timestamp('scheduled_time'); // When to post
            $table->string('platform'); // Platform to post to
            $table->boolean('is_posted')->default(false); // Status of posting
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('scheduled_posts');
    }
}

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('messages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('from_user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('to_user_id')->constrained('users')->onDelete('cascade');
            $table->string('subject');
            $table->text('content');
            $table->timestamp('read_at')->nullable();
            $table->boolean('is_archived')->default(false);
            $table->boolean('is_starred')->default(false);
            $table->timestamps();
            
            // Add indexes for better query performance
            $table->index('from_user_id');
            $table->index('to_user_id');
            $table->index('read_at');
        });
    }

    public function down()
    {
        Schema::dropIfExists('messages');
    }
};
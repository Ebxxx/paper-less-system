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
        Schema::table('message_marks', function (Blueprint $table) {
            $table->enum('pre_reply', ['approved', 'rejected', 'thank_you'])->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::table('message_marks', function (Blueprint $table) {
            $table->dropColumn('pre_reply');
        });
    }
};

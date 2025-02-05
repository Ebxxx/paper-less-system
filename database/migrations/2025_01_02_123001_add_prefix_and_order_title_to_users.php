<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('prefix')->nullable()->after('last_name'); // For titles like Dr., Mr., Ms., etc.
            $table->text('order_title')->nullable()->after('prefix'); // For storing ordered academic titles
        });
    }

    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['prefix', 'order_title']);
        });
    }
}; 
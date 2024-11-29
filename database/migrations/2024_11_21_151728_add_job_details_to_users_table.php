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
        Schema::table('users', function (Blueprint $table) {
            $table->string('job_title')->nullable()->after('signature_path');
            $table->string('program')->nullable()->after('job_title');
            $table->string('department')->nullable()->after('program');
        });
    }
    
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['job_title', 'program', 'department']);
        });
    }
};

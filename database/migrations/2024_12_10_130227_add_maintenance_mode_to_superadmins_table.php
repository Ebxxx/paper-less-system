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
        Schema::table('superadmins', function (Blueprint $table) {
            $table->boolean('maintenance_mode')->default(false);
        });
    }
    
    public function down()
    {
        Schema::table('superadmins', function (Blueprint $table) {
            $table->dropColumn('maintenance_mode');
        });
    }
};

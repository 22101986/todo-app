<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('articles', function (Blueprint $table) {
            $table->text('content')->change();
        });
    }
    
    public function down()
    {
        Schema::table('articles', function (Blueprint $table) {
            $table->string('content')->change();
        });
    }
};

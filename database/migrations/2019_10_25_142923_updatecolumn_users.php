<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdatecolumnUsers extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (Schema::hasTable('events')) {
            Schema::table('events', function (Blueprint $table) {
                $table->string('register_button')->nullable()->change();
                $table->string('is_register_url')->nullable()->change();
                $table->string('register_url')->nullable()->change();
                $table->string('directions_button')->nullable()->change();
                
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}

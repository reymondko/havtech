<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddMandatoryToEventSchedule extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (Schema::hasTable('event_schedule')) {
            Schema::table('event_schedule', function (Blueprint $table) {
                $table->integer('mandatory')->default(0);  
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
        Schema::table('event_schedule', function (Blueprint $table) {
            //
        });
    }
}

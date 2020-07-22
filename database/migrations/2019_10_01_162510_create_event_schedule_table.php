<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEventScheduleTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('event_schedule', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('event_id');
            $table->dateTime('start_date')->nullable();
            $table->dateTime('end_date')->nullable();
            $table->string('description')->nullable();            
            $table->string('location')->nullable();             
            $table->string('image')->nullable();              
            $table->string('website_url')->nullable()->default(NULL);               
            $table->string('directions_url')->nullable()->default(NULL);           
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('event_schedule');
    }
}

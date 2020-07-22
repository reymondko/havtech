<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEventsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('events', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('event_types')->nullable();
            $table->string('event_name')->nullable();
            $table->dateTime('start_date')->nullable();
            $table->dateTime('end_date')->nullable();
            $table->string('description')->nullable();            
            $table->string('image')->nullable()->default(NULL);            
            $table->string('dinning_image')->nullable()->default(NULL);            
            $table->string('transportation_image')->nullable()->default(NULL);           
            $table->string('schedule_image')->nullable()->default(NULL);          
            $table->string('accomodations_image')->nullable()->default(NULL);          
            $table->string('event_host')->nullable();          
            $table->string('event_status')->nullable();
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
        Schema::dropIfExists('events');
    }
}

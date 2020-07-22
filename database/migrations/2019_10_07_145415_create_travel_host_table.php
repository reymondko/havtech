<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTravelHostTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('event_travel_hosts', function (Blueprint $table) {
            $table->increments('id');    
            $table->integer('event_id');
            $table->string('host_name')->nullable();             
            $table->string('address1')->nullable();             
            $table->string('address2')->nullable();              
            $table->string('email_button')->nullable();              
            $table->string('email')->nullable();  
            $table->string('description')->nullable();             
            $table->string('thumb_image')->nullable();              
            $table->string('image')->nullable();          
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
        Schema::dropIfExists('event_travel_hosts');
    }
}

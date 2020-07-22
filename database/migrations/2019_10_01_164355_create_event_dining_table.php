<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEventDiningTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('event_dining', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('event_id')->nullable(); 
            $table->string('dining_type')->nullable(); 
            $table->dateTime('meal_date')->nullable();  
            $table->dateTime('name')->nullable();           
            $table->string('address1')->nullable();
            $table->string('address2')->nullable()->default(NULL);
            $table->string('city')->nullable();
            $table->string('state')->nullable();
            $table->string('zip')->nullable();
            $table->string('phone')->nullable();
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
        Schema::dropIfExists('event_dining');
    }
}

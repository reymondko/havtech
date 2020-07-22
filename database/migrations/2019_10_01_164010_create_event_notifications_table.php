<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEventNotificationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('event_notifications', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('event_id')->nullable(); 
            $table->string('event_title')->nullable();
            $table->string('description')->nullable();
            $table->string('button_label')->nullable();            
            $table->string('button_url')->nullable();
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
        Schema::dropIfExists('event_notifications');
    }
}

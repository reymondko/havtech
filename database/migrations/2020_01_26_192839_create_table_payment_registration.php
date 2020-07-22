<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTablePaymentRegistration extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('payments_registration', function (Blueprint $table) {
            $table->increments('id');
            $table->string('event_id')->nullable();
            $table->string('user_id')->nullable(); 
            $table->string('number_persons')->nullable(); 
            $table->string('amount')->nullable();
            $table->text('magothy_response')->nullable();
            
            $table->string('order_id')->nullable();
            $table->string('transaction_id')->nullable();
            $table->string('payment_status')->nullable();
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
        Schema::dropIfExists('payments_registration');
    }
}

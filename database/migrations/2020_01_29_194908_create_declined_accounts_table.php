<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDeclinedAccountsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('declined_accounts', function (Blueprint $table) {
            $table->increments('id');
            $table->string('email');
            $table->string('password')->nullable();
            $table->string('company')->nullable(); 
            $table->integer('customer_type')->nullable();
            $table->string('industry')->nullable(); 
            $table->string('first_name')->nullable();
            $table->string('last_name')->nullable();            
            $table->string('address1')->nullable();
            $table->string('address2')->nullable()->default(NULL);
            $table->string('city')->nullable();
            $table->string('state')->nullable();
            $table->string('zip')->nullable();
            $table->string('phone')->nullable();
            $table->string('title')->nullable();
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
        Schema::dropIfExists('declined_accounts');
    }
}

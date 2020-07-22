<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnCustomeventSchedule extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (Schema::hasTable('custom_event_schedule')) {
            Schema::table('custom_event_schedule', function (Blueprint $table) {
                $table->integer('registration_id')->after('user_id')->nullable();  
                $table->integer('payment_id')->after('registration_id')->nullable();   
                $table->integer('payment_status')->after('payment_id')->nullable(); 
            });
        }
        //
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        if (Schema::hasTable('custom_event_schedule')) {
            Schema::table('custom_event_schedule', function (Blueprint $table) {
                $table->dropColumn('registration_id');
                $table->dropColumn('payment_id');
                $table->dropColumn('payment_status');
            });
        }
    }
}

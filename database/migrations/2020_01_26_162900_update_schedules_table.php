<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateSchedulesTable extends Migration
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
                $table->integer('price')->nullable();  
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
        if (Schema::hasTable('event_schedule')) {
            Schema::table('event_schedule', function (Blueprint $table) {
                $table->dropColumn('price');
            });
        }
    }
}

<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChangeColumnsEventTransportationTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (Schema::hasTable('event_transportation')) {
            Schema::table('event_transportation', function (Blueprint $table) {
                $table->string('company_name')->nullable()->default(NULL)->change();        
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
        if (Schema::hasTable('event_transportation')) {
            Schema::table('event_transportation', function (Blueprint $table) {
                $table->dateTime('company_name')->nullable()->change();
            });
        } 
    }
}

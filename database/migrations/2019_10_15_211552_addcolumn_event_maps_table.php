<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddcolumnEventMapsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    { 
        if (Schema::hasTable('event_maps')) {
            Schema::table('event_maps', function (Blueprint $table) {
                $table->string('map_name')->after('event_id');
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
        if (Schema::hasTable('event_maps')) {
            Schema::table('event_maps', function (Blueprint $table) {
                $table->dropColumn('map_name');
            });
        }
    }
}

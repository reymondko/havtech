<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddcolumnsEventSchedulePart3Table extends Migration
{
    public function up()
    {
        if (Schema::hasTable('event_schedule')) {
            Schema::table('event_schedule', function (Blueprint $table) {
                $table->string('room_number')->after('location')->nullable();  
                $table->string('phone')->after('zip')->nullable();
                $table->string('country')->after('phone')->nullable();  
                $table->string('download_link')->after('country')->nullable();
                $table->string('itinerary_file')->after('download_link')->nullable();
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
                $table->dropColumn('room_number');
                $table->dropColumn('phone');
                $table->dropColumn('country');
                $table->dropColumn('download_link');
                $table->dropColumn('itinerary_file');
            });
        }
    }
}

<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddcolumnsEventAccomodationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (Schema::hasTable('event_accomodations')) {
            Schema::table('event_accomodations', function (Blueprint $table) {
                $table->string('hotel')->after('event_id')->nullable()->default(NULL);
                $table->string('confirmation_number')->after('name')->nullable()->default(NULL);
                $table->string('room_number')->after('confirmation_number')->nullable()->default(NULL);
                $table->string('location')->after('room_number')->nullable()->default(NULL);
                $table->string('description')->after('phone')->nullable()->default(NULL);
                $table->string('directions_button')->after('description')->nullable()->default(NULL);     
                                        
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
        if (Schema::hasTable('event_accomodations')) {
            Schema::table('event_accomodations', function (Blueprint $table) {
                $table->dropColumn('hotel');
                $table->dropColumn('confirmation_number');
                $table->dropColumn('room_number');
                $table->dropColumn('location');
                $table->dropColumn('description');
                $table->dropColumn('directions_button');
            });
        }
    }
}

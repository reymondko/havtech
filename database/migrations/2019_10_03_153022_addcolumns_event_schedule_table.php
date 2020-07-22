<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddcolumnsEventScheduleTable extends Migration
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
                $table->string('title')->after('event_id')->nullable()->default(NULL);
                $table->string('location_address')->after('location')->nullable()->default(NULL);
                $table->string('location_address2')->after('location_address')->nullable()->default(NULL);
                $table->string('city')->after('location_address2')->nullable()->default(NULL);
                $table->string('state')->after('city')->nullable()->default(NULL);
                $table->string('thumb_image')->after('image')->nullable()->default(NULL);
                $table->string('directions_button')->after('thumb_image')->nullable()->default(NULL);     
                                        
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
                $table->dropColumn('title');
                $table->dropColumn('location_address');
                $table->dropColumn('location_address2');
                $table->dropColumn('city');
                $table->dropColumn('state');
                $table->dropColumn('thumb_image');
                $table->dropColumn('directions_button');
            });
        }
    }
}

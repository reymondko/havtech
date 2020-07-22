<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAddColumnsEventsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (Schema::hasTable('events')) {
            Schema::table('events', function (Blueprint $table) {
                $table->integer('register_button')->after('event_status')->default('0');
                $table->integer('is_register_url')->after('register_button')->default('0');
                $table->string('register_url')->after('is_register_url')->nullable()->default(NULL);
                $table->string('directions_button')->after('register_url')->nullable()->default(NULL);
                $table->string('map')->after('accomodations_image')->nullable()->default(NULL);  
                $table->string('travelhost')->after('map')->nullable()->default(NULL); 
                $table->renameColumn('dinning_image', 'dining_image');
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
        if (Schema::hasTable('events')) {
            Schema::table('events', function (Blueprint $table) {
                $table->dropColumn('register_button');
                $table->dropColumn('is_register_url');
                $table->dropColumn('register_url');
                $table->dropColumn('directions_button');
                $table->dropColumn('map');
                $table->dropColumn('travelhost');
                $table->renameColumn('dining_image', 'dinning_image');
            });
        }
    }
}

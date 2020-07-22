<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddcolumnsEventDiningTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (Schema::hasTable('event_dining')) {
            Schema::table('event_dining', function (Blueprint $table) {
                $table->string('location')->after('dining_type')->nullable()->default(NULL);
                $table->string('start_time')->after('meal_date')->nullable()->default(NULL);
                $table->string('end_time')->after('start_time')->nullable()->default(NULL);
                $table->string('description')->after('phone')->nullable()->default(NULL);
                $table->string('directions_button')->after('description')->nullable()->default(NULL);
                $table->string('website_url')->after('directions_button')->nullable()->default(NULL);
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
        if (Schema::hasTable('event_dining')) {
            Schema::table('event_dining', function (Blueprint $table) {
                $table->dropColumn('location');
                $table->dropColumn('start_time');
                $table->dropColumn('end_time');
                $table->dropColumn('description');
                $table->dropColumn('directions_button');
                $table->dropColumn('website_url');
            });
        }
    }
}

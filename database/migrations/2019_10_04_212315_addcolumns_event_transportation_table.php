<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddcolumnsEventTransportationTable extends Migration
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
                $table->string('image')->after('phone')->nullable()->default(NULL);
                $table->string('service_address1')->after('company_name')->nullable()->default(NULL);
                $table->string('service_address2')->after('service_address1')->nullable()->default(NULL);
                $table->string('description')->after('phone')->nullable()->default(NULL);
                $table->string('directions_button')->after('description')->nullable()->default(NULL);
                $table->string('website_url')->after('directions_button')->nullable()->default(NULL);
                $table->string('flight_description')->after('website_url')->nullable()->default(NULL);               
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
                $table->dropColumn('image');
                $table->dropColumn('service_address1');
                $table->dropColumn('service_address2');
                $table->dropColumn('description');
                $table->dropColumn('directions_button');
                $table->dropColumn('website_url');
                $table->dropColumn('flight_description');
            });
        }
    }
}

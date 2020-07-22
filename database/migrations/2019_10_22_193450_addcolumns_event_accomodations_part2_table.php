<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddcolumnsEventAccomodationsPart2Table extends Migration
{
    public function up()
    {
        if (Schema::hasTable('event_accomodations')) {
            Schema::table('event_accomodations', function (Blueprint $table) {
                $table->string('country')->after('zip')->nullable();  
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
                $table->dropColumn('country');
            });
        }
    }
}

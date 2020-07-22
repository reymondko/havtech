<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddcolumnsEventDiningPart2Table extends Migration
{
    public function up()
    {
        if (Schema::hasTable('event_dining')) {
            Schema::table('event_dining', function (Blueprint $table) {
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
        if (Schema::hasTable('event_dining')) {
            Schema::table('event_dining', function (Blueprint $table) {
                $table->dropColumn('country');
            });
        }
    }
}

<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddcolumnsEventSchedulePart2Table extends Migration
{
    public function up()
    {
        if (Schema::hasTable('event_schedule')) {
            Schema::table('event_schedule', function (Blueprint $table) {
                $table->string('presenter_name')->after('title')->nullable();  
                $table->string('presenter_company')->after('presenter_name')->nullable();
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
                $table->dropColumn('presenter_name');
                $table->dropColumn('presenter_company');
            });
        }
    }
}

<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddcolumnsEventOverviewPart2Table extends Migration
{
    public function up()
    {
        if (Schema::hasTable('events')) {
            Schema::table('events', function (Blueprint $table) {
                $table->renameColumn('visibility', 'visibility_app');
                $table->string('visibility_web')->after('event_status')->nullable();
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
                $table->renameColumn('visibility_app', 'visibility');
                $table->dropColumn('visibility_web');
            });
        }
    }
}

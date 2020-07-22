<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnEventsOverviewTable extends Migration
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
                $table->text('overview_file')->after('image')->nullable();        
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
                $table->dropColumn('overview_file');
            });
        }
    }
}

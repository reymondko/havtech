<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdatecolumnsEventsTablePart4 extends Migration
{
    public function up()
    {
        if (Schema::hasTable('events')) {
            Schema::table('events', function (Blueprint $table) {
                $table->string('event_host_title')->after('event_host')->nullable(); 
                $table->text('event_host_description')->after('event_host_title')->nullable(); 
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
                $table->dropColumn('event_host_title');
                $table->dropColumn('event_host_description');
            });
        }
    }
}

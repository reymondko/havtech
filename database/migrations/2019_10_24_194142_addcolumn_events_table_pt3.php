<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddcolumnEventsTablePt3 extends Migration
{
    public function up()
    {
        if (Schema::hasTable('events')) {
            Schema::table('events', function (Blueprint $table) {
                $table->string('directions_url')->after('directions_button')->nullable();  
                $table->string('website_url')->after('directions_url')->nullable(); 
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
                $table->dropColumn('directions_url');
                $table->dropColumn('website_url');
            });
        }
    }
}

<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddcolumnsEventOverviewTable extends Migration
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
                $table->string('cost_per_person')->after('directions_button')->nullable();   
                $table->string('number_of_person')->after('cost_per_person')->nullable();       
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
                $table->dropColumn('cost_per_person');
                $table->dropColumn('number_of_person');
            });
        }
    }
}

<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdatecolumnsDescriptions extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('events', function (Blueprint $table) {
            $table->text('description')->change();
        });
        Schema::table('event_schedule', function (Blueprint $table) {
            $table->text('description')->change();
        });
        Schema::table('event_accomodations', function (Blueprint $table) {
            $table->text('description')->change();
        });
        Schema::table('event_dining', function (Blueprint $table) {
            $table->text('description')->change();
        });
        Schema::table('event_transportation', function (Blueprint $table) {
            $table->text('description')->change();
        });
        Schema::table('event_travel_hosts', function (Blueprint $table) {
            $table->text('description')->change();
        });
        Schema::table('event_faqs', function (Blueprint $table) {
            $table->text('faq_answer')->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}

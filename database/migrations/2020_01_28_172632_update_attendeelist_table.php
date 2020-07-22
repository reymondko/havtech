<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateAttendeelistTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (Schema::hasTable('event_attendees')) {
            Schema::table('event_attendees', function (Blueprint $table) {
                $table->integer('email_sent_approved')->default(0)->nullable();  
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
        if (Schema::hasTable('event_attendees')) {
            Schema::table('event_attendees', function (Blueprint $table) {
                $table->dropColumn('email_sent_approved');
            });
        }
    }
}

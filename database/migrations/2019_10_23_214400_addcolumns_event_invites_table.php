<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddcolumnsEventInvitesTable extends Migration
{
    public function up()
    {
        if (Schema::hasTable('event_attendees')) {
            Schema::table('event_attendees', function (Blueprint $table) {
                $table->string('email_sent')->after('phone')->default(0)->nullable();  
                $table->string('email_read')->after('email_sent')->default(0)->nullable();
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
                $table->dropColumn('email_sent');
                $table->dropColumn('email_read');
            });
        }
    }
}

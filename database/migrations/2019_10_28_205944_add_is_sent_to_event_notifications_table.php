<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddIsSentToEventNotificationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (Schema::hasTable('event_notifications')) {
            Schema::table('event_notifications', function (Blueprint $table) {
                $table->integer('is_sent')->after('is_read')->default(0); 
                $table->dateTime('sent_date')->after('is_sent')->nullable(); 
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
        if (Schema::hasTable('event_notifications')) {
            Schema::table('event_notifications', function (Blueprint $table) {
                $table->dropColumn('is_sent');
            });
        }
    }
}

<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddcolumnsEventNotifications2Table extends Migration
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
                $table->integer('is_read')->nullable()->after('button_url');
                $table->integer('with_button_url')->nullable()->after('button_label');
            });
        }
        if (Schema::hasTable('event_notifications_history')) {
            Schema::table('event_notifications_history', function (Blueprint $table) {
                $table->integer('is_read')->nullable()->after('is_sent');
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
                $table->dropColumn('is_read');
                $table->dropColumn('with_button_url');
            });
        }
        if (Schema::hasTable('event_notifications_history')) {
            Schema::table('event_notifications_history', function (Blueprint $table) {
                $table->dropColumn('is_read');
            });
        }
    }
}

<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class RenamecolumnEventNotificationsHistoryTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (Schema::hasTable('event_notifications_history')) {
            Schema::table('event_notifications_history', function (Blueprint $table) {
                $table->renameColumn('users_id', 'user_id');
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
        if (Schema::hasTable('event_notifications_history')) {
            Schema::table('event_notifications_history', function (Blueprint $table) {
                $table->renameColumn('user_id', 'users_id');
            });
        }
    }
}

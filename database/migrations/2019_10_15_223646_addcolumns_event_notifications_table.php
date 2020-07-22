<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddcolumnsEventNotificationsTable extends Migration
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
                $table->string('notif_date')->after('description');
                $table->string('visibility')->after('button_url');
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
                $table->dropColumn('notif_date');
                $table->dropColumn('visibility');
            });
        }
    }
}

<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class RenamecolumnEventNotificationsTable extends Migration
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
                $table->renameColumn('event_title', 'title');
                $table->renameColumn('button_label', 'button_link');
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
                $table->renameColumn('title', 'event_title');
                $table->renameColumn('button_link', 'button_label');
            });
        }
    }
}

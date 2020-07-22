<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddcolumnEventNotificationsTablePt2 extends Migration
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
                $table->string('send_as')->after('button_url')->default("push_notification")->nullable();  
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
                $table->dropColumn('send_as');
            });
        }
    }
}

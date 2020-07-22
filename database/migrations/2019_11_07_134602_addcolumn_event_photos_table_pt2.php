<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddcolumnEventPhotosTablePt2 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (Schema::hasTable('event_photos')) {
            Schema::table('event_photos', function (Blueprint $table) {
                $table->integer('pending')->after('user_id')->default(1)->nullable(); 
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
        if (Schema::hasTable('event_photos')) {
            Schema::table('event_photos', function (Blueprint $table) {
                $table->dropColumn('pending');
            });
        }
    }
}

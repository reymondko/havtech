<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddcolumnsEventPhotosTable extends Migration
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
                $table->string('filename')->after('event_id');
                $table->string('resized_name')->after('filename');
                $table->string('original_name')->after('resized_name');
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
                $table->dropColumn('filename');
                $table->dropColumn('resized_name');
                $table->dropColumn('original_name');
            });
        }
    }
}

<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class RenameLievents extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (Schema::hasTable('event_type')) {
            DB::table('event_type')
                ->where('id',3)
                ->update([
                    "description" => 'Learning Institute'
                ]);
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        if (Schema::hasTable('event_type')) {
            DB::table('event_type')
                ->where('id',3)
                ->update([
                    "description" => 'LI Events'
                ]);
        
        }
    }
}

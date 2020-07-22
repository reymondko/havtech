<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnsEventFaqsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (Schema::hasTable('event_faqs')) {
            Schema::table('event_faqs', function (Blueprint $table) {
                $table->string('download_link')->after('faq_answer')->nullable()->default(NULL); 
                $table->string('event_info_file')->after('download_link')->nullable()->default(NULL);           
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
        if (Schema::hasTable('event_faqs')) {
            Schema::table('event_faqs', function (Blueprint $table) {
                $table->dropColumn('event_info_file');
                $table->dropColumn('download_link');
            });
        }
    }
}

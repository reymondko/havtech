<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnsEventsOverviewpart6 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (Schema::hasTable('events')) {
            Schema::table('events', function (Blueprint $table) {
                $table->string('One23form_section')->after('custom_calendar_message')->nullable(); 
                $table->string('One23form_title')->after('One23form_section')->nullable();    
                $table->string('One23form_button_name')->after('One23form_title')->nullable();    
                $table->text('One23form_description')->after('One23form_button_name')->nullable();  
                $table->text('One23form_embed_code')->after('One23form_description')->nullable();              
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
        if (Schema::hasTable('events')) {
            Schema::table('events', function (Blueprint $table) {
                $table->dropColumn('One23form_section');
                $table->dropColumn('One23form_title');
                $table->dropColumn('One23form_button_name');
                $table->dropColumn('One23form_description');
                $table->dropColumn('One23form_embed_code');
            });
        }
    }
}

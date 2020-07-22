<?php

use Illuminate\Database\Seeder;
use App\Models\EventTypes;

class EventTypesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('event_type')->truncate();
        $items = [ 
            ['id' => 1, 'description' => 'General Events'],           
            ['id' => 2, 'description' => 'Special Events'],
            ['id' => 3, 'description' => 'Learning Institute'],
            ['id' => 4, 'description' => 'Archive Events'],
        ];
        foreach ($items as $item) {
            EventTypes::updateOrCreate(['id' => $item['id']], $item);
        }
    }
}

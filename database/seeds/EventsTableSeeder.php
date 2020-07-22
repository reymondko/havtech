<?php

use Illuminate\Database\Seeder;
use App\Models\Events;

class EventsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Events::create([
            'event_types' => '1',
            'event_name' => 'Event 1',
            'start_date' => '2019-10-01 03:00:00',
            'end_date' => '2019-10-01 09:00:00',
            'description' => 'Test Event 1',
            'event_host' => 'Digiance',
            'event_status' => '1'
        ]);
        Events::create([
            'event_types' => '2',
            'event_name' => 'Event 2',
            'start_date' => '2019-10-02 03:00:00',
            'end_date' => '2019-10-02 09:00:00',
            'description' => 'Test Event 2',
            'event_host' => 'Digiance',
            'event_status' => '1'
        ]);
        Events::create([
            'event_types' => '3',
            'event_name' => 'Event 3',
            'start_date' => '2019-10-03 03:00:00',
            'end_date' => '2019-10-03 09:00:00',
            'description' => 'Test Event 3',
            'event_host' => 'Digiance',
            'event_status' => '1'
        ]);
        Events::create([
            'event_types' => '4',
            'event_name' => 'Event 4',
            'start_date' => '2019-10-04 03:00:00',
            'end_date' => '2019-10-04 09:00:00',
            'description' => 'Test Event 4',
            'event_host' => 'Digiance',
            'event_status' => '1'
        ]);
        Events::create([
            'event_types' => '1',
            'event_name' => 'Event 5',
            'start_date' => '2019-10-05 03:00:00',
            'end_date' => '2019-10-05 09:00:00',
            'description' => 'Test Event 5',
            'event_host' => 'Digiance',
            'event_status' => '1'
        ]);

    }
}

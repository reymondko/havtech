<?php

use Illuminate\Database\Seeder;

use App\Models\DiningTypes;

class DiningTypesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DiningTypes::create([
            'name' => 'Breakfast',
        ]);
        DiningTypes::create([
            'name' => 'Lunch',
        ]);
        DiningTypes::create([
            'name' => 'Dinner',
        ]);
    }
}

<?php

use Illuminate\Database\Seeder;

class CustomerTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $types = [
                ['type' => 'Guest'],
                ['type' => 'Havtech'],
                ['type' => 'Mechanical Engineer'],
                ['type' => 'Mechanical Contractor'],
                ['type' => 'Manufacturer'],
                ['type' => 'Owner'],
            ];
        
        DB::table('customer_type')->insert($types);
       
    }
}

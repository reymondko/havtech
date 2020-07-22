<?php

use Illuminate\Database\Seeder;

use App\Models\EventIndustries;

class EventIndustriesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        EventIndustries::create(['industry_name' => 'Aerospace']);
        EventIndustries::create(['industry_name' => 'Transport']);
        EventIndustries::create(['industry_name' => 'Computer']);
        EventIndustries::create(['industry_name' => 'Telecommunication']);
        EventIndustries::create(['industry_name' => 'Agriculture']);
        EventIndustries::create(['industry_name' => 'Construction']);
        EventIndustries::create(['industry_name' => 'Pharmaceutical']);
        EventIndustries::create(['industry_name' => 'Healthcare']);
        EventIndustries::create(['industry_name' => 'Hospitality']);
        EventIndustries::create(['industry_name' => 'Entertainment']);
        EventIndustries::create(['industry_name' => 'News Media']);
        EventIndustries::create(['industry_name' => 'Music']);
        EventIndustries::create(['industry_name' => 'Electronics']);
    }
}

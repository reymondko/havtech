<?php

use Illuminate\Database\Seeder;

class UserRolesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('user_roles')->insert(
            [
                [
                    'id' => 1,
                    'description' => 'Administrator',
                ],
                [
                    'id' => 2,
                    'description' => 'User',
                ]
            ]
        );
    }
}

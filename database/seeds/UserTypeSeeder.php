<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class UserTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data = [
            [
                'id' => Str::uuid(),
                'name' => 'normal',
                'max_traffic' => 30,
            ],
            [
                'id' => Str::uuid(),
                'name' => 'vip',
                'max_traffic' => 1000,
            ]
        ];

        DB::table('user_types')->insert($data);
    }
}

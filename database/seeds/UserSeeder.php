<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $type =  DB::table('page_types')->first();
        $data = [
            [
                'id' => Str::uuid(),
                // 'email' => 'admin@gmail.com',
                'password' => bcrypt('12341234'),
                'username' => 'Admin',
                'status' => 1,
                'is_admin' => 1,
                'wallet' => 0,
                'page_type_id' => $type->id,
            ],
            [
                'id' => Str::uuid(),
                // 'email' => 'admin@gmail.com',
                'password' => bcrypt('12341234'),
                'username' => 'dlha',
                'status' => 1,
                'is_admin' => 0,
                'wallet' => 99999999,
                'page_type_id' => $type->id,
            ]
        ];

        DB::table('users')->insert($data);
    }
}

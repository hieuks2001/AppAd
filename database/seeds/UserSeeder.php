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
        $type =  DB::table('user_types')->where('name', 'normal')->first();
        $data = [
            [
                'id' => Str::uuid(),
                // 'email' => 'admin@gmail.com',
                'password' => bcrypt('12341234'),
                'username' => 'Admin',
                'status' => 1,
                'is_admin' => 1,
                'wallet' => 0,
                'user_type_id' => $type->id,
            ],
            [
                'id' => Str::uuid(),
                // 'email' => 'admin@gmail.com',
                'password' => bcrypt('12341234'),
                'username' => 'dlha',
                'status' => 1,
                'is_admin' => 0,
                'wallet' => 9999999999,
                'user_type_id' => $type->id,
            ]
        ];

        DB::table('users')->insert($data);
    }
}

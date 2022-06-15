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
        $data = [
            [
                'user_uuid' => Str::uuid(),
                // 'email' => 'admin@gmail.com',
                'password' => bcrypt('12341234'),
                'username' => 'Admin',
                'status' => 1,
                'isAdmin' => 1
            ],
        ];

        DB::table('users')->insert($data);
    }
}

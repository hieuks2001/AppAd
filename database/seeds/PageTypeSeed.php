<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class PageTypeSeed extends Seeder
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
                'name' => 'Loại 1',
                'onsite' => json_encode([
                    60 => 0.055,
                    70 => 0.065,
                    90 => 0.075,
                    120 => 0.08,
                    150 => 0.09,
                ]),
                'mission_need' => 0
            ],
            [
                'id' => Str::uuid(),
                'name' => 'Loại 2',
                'onsite' => json_encode([
                    60 => 0.075,
                    70 => 0.085,
                    90 => 0.095,
                    120 => 0.1,
                    150 => 0.110,
                ]),
                'mission_need' => 1
            ]
        ];

        DB::table('page_types')->insert($data);
    }
}

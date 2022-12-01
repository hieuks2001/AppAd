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
        'name' => '1',
        'onsite' => json_encode([
          60 => 0.055 * 23000,
          70 => 0.065 * 23000,
          90 => 0.075 * 23000,
          120 => 0.08 * 23000,
          150 => 0.09 * 23000,
        ]),
      ],
      [
        'id' => Str::uuid(),
        'name' => '2',
        'onsite' => json_encode([
          60 => 0.075 * 23000,
          70 => 0.085 * 23000,
          90 => 0.095 * 23000,
          120 => 0.1 * 23000,
          150 => 0.110 * 23000,
        ]),
      ],
      [
        'id' => Str::uuid(),
        'name' => '3',
        'onsite' => json_encode([
          60 => 0.075 * 23000,
          70 => 0.085 * 23000,
          90 => 0.095 * 23000,
          120 => 0.1 * 23000,
          150 => 0.110 * 23000,
        ]),
      ]
    ];

    DB::table('page_types')->insert($data);
  }
}

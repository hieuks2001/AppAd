<?php

use App\Models\PageType;
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
    $pageTypes = PageType::get();
    $pageTypes = $pageTypes->mapWithKeys(function($pageType){
      return [$pageType->name => $pageType->id];
    });
    $data = [
      [
        'id' => Str::uuid(),
        'name' => 'Normal',
        'mission_need' => json_encode([
          $pageTypes['1'] => 20,
          $pageTypes['2'] => 20,
        ]),
        'page_weight' => json_encode([
          $pageTypes['1'] => 50,
          $pageTypes['2'] => 30,
          $pageTypes['3'] => 20,
        ]),
        'is_default' => 1
      ],
      [
        'id' => Str::uuid(),
        'name' => 'Middle',
        'mission_need' => json_encode([
          $pageTypes['1'] => 5,
          $pageTypes['2'] => 10,
        ]),
        'page_weight' => json_encode([
          $pageTypes['1'] => 30,
          $pageTypes['2'] => 50,
          $pageTypes['3'] => 20,
        ]),
        'is_default' => 0
      ],
      [
        'id' => Str::uuid(),
        'name' => 'VIP',
        'mission_need' => json_encode([
          $pageTypes['1'] => 0,
          $pageTypes['2'] => 5,
        ]),
        'page_weight' => json_encode([
          $pageTypes['1'] => 10,
          $pageTypes['2'] => 40,
          $pageTypes['3'] => 50,
        ]),
        'is_deault' => 0,
      ]
    ];

    DB::table('user_types')->insert($data);
  }
}

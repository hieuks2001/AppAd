<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMissionsTable extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::create('missions', function (Blueprint $table) {
      $table->uuid('id')->primary()->unique();
      $table->uuid('page_id');
      $table->uuid('user_id')->nullable();
      $table->decimal('reward', 5, 4)->nullable();
      $table->string('ip')->default('');
      $table->string('user_agent')->default('');
      $table->tinyInteger('status');
      $table->string('code')->default('');
      $table->string('origin_url')->default('');
      $table->timestamps();
    });
  }

  /**
   * Reverse the migrations.
   *
   * @return void
   */
  public function down()
  {
    Schema::dropIfExists('missions');
  }
}

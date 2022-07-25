<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersMissionTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_missions', function (Blueprint $table) {
          $table->uuid('id')->primary();
          $table->string('username');
          $table->string('password');
          $table->string('phone_number')->nullable();
          $table->decimal('wallet', 19, 4)->nullable();
          $table->bigInteger('commission')->nullable();
          $table->tinyInteger('status')->default(1);
          // $table->integer('mission_count')->default(0);
          $table->json('mission_count')->nullable();
          $table->tinyInteger('mission_attempts')->default(0);
          $table->tinyInteger('is_admin')->default(0);
          $table->foreignUuid('user_type_id')->constrained();
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
        Schema::dropIfExists('users_mission');
    }
}

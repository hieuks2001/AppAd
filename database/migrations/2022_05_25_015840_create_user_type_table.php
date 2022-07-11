<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserTypeTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_types', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('name');
            $table->json('mission_need');
            $table->json('page_weight'); # percentages to get page mission
            $table->tinyInteger('is_default')->default(0); # is default for new user
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
        // Schema::table('user_types', function (Blueprint $table) {
        //     Schema::dropIfExists('user_types');
        // });
    }
}

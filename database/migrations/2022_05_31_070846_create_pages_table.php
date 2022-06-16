<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pages', function (Blueprint $table) {
            $table->uuid('id')->primary()->unique();
            $table->uuid('user_uuid');
            $table->string('keyword')->nullable();
            $table->string('image')->nullable();
            $table->string('url');
            $table->integer('traffic_per_day');
            $table->integer('traffic_sum');
            $table->integer('onsite');
            $table->float('price');
            $table->tinyInteger('is_approved')->default(0);
            $table->tinyInteger('status')->nullable();
            $table->tinyInteger('priority')->default(0);
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
        Schema::dropIfExists('pages');
    }
}

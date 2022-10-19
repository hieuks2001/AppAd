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
            $table->foreignUuid('user_id')->constrained('user_traffics');
            $table->string('keyword')->nullable();
            $table->string('image')->nullable();
            $table->string('url');
            $table->integer('traffic_per_day');
            $table->integer('traffic_sum');
            $table->integer('traffic_remain');
            $table->integer('onsite');
            $table->decimal('price_per_traffic', 20, 5);
            $table->decimal('price', 20, 5);
            $table->decimal('hold_percentage')->default(10);
            $table->tinyInteger('status')->default(0);
            $table->tinyInteger('priority')->default(0);
            $table->foreignUuid('page_type_id')->constrained();
            $table->string('note')->nullable();
            $table->time('timeout')->default('02:00:00');
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

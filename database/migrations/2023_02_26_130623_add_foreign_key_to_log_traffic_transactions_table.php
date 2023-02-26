<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddForeignKeyToLogTrafficTransactionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
      Schema::table('log_mission_transactions', function (Blueprint $table) {
        // Add foreign key constraint to user_id column
        $table->foreign('user_id')->references('id')->on('user_missions');

        // Add foreign key constraint to from_user_id column
        $table->foreign('from_user_id')->nullable()->references('id')->on('user_missions');
      });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('log_traffic_transactions', function (Blueprint $table) {
            //
        });
    }
}

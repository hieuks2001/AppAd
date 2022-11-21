<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddBeforeAfterColumnstToLogMissionTransactionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('log_mission_transactions', function (Blueprint $table) {
          $table->float('before', 9, 5)->after('amount')->nullable();
          $table->float('after', 9, 5)->after('before')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('log_mission_transactions', function (Blueprint $table) {
          $table->dropColumn(['before', 'after']);
        });
    }
}

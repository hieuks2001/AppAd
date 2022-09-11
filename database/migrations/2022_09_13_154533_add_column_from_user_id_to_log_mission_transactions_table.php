<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnFromUserIdToLogMissionTransactionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('log_mission_transactions', function (Blueprint $table) {
            $table->uuid("from_user_id")->after("user_id")->nullable(true);
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
            $table->dropColumn(["from_user_id"]);
        });
    }
}

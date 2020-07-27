<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterTableAddFlowAwardIdInCashFlows extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('cash_flows', function (Blueprint $table) {
            $table->unsignedBigInteger('flow_award_id')->nullable()
                ->after('flow_bill_id');

            $table->foreign('flow_award_id')->on('awards')
                ->references('id')
                ->onDelete('cascade')
                ->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('cash_flows', function (Blueprint $table) {
            Schema::drop('cash_flows');
        });
    }
}

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCashFlowsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cash_flows', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->date('flow_movement_date')->default(today());

            $table->unsignedBigInteger('flow_bank_id')->nullable();
            $table->foreign('flow_bank_id')->references('id')
                ->on('banks');

            $table->unsignedBigInteger('flow_bill_id')->nullable();
            $table->foreign('flow_bill_id')->references('id')
                ->on('bills');

            $table->unsignedBigInteger('flow_receive_id')->nullable();
            $table->foreign('flow_receive_id')->references('id')
                ->on('receives')
                ->onDelete('cascade')
                ->onUpdate('cascade');

            $table->date('flow_bill_payment_date')->nullable();
            $table->date('flow_receive_payment_date')->nullable();
            $table->date('flow_award_generated_shipment')->nullable();
            $table->boolean('flow_hide_line')->default(0);

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
        Schema::dropIfExists('cash_flows');
    }
}

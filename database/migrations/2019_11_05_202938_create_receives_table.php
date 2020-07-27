<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateReceivesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('receives', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->decimal('awarded_value', 11, 4);
            $table->decimal('receive_award_real_value', 11, 4);
            $table->decimal('receive_taxable_real_value', 11, 4);
            $table->date('receive_date_receipt');
            $table->integer('receive_status');

            $table->unsignedBigInteger('receive_demand_id');
            $table->foreign('receive_demand_id')->on('demands')
                ->references('id');

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
        Schema::dropIfExists('receives');
    }
}

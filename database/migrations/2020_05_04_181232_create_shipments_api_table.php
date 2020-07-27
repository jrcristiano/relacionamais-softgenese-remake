<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateShipmentsApiTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('shipments_api', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->unsignedBigInteger('shipment_award_id');
            $table->foreign('shipment_award_id')->references('id')
                ->on('awards')
                ->onDelete('cascade')
                ->onUpdate('cascade');

            $table->boolean('shipment_generated')->nullable();

            $table->unsignedBigInteger('shipment_last_field')
                ->nullable();

            $table->string('shipment_file')->nullable();

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
        Schema::dropIfExists('shipments_api');
    }
}

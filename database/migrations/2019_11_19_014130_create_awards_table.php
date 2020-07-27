<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAwardsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('awards', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->decimal('awarded_value', 11, 4);
            $table->string('awarded_type');
            $table->smallInteger('awarded_status')->nullable();

            $table->smallInteger('awarded_type_card')->nullable()
                ->unsigned();

            $table->string('awarded_upload_table')->nullable();
            $table->boolean('awarded_shipment_cancelled')->nullable();

            $table->bigInteger('awarded_demand_id')->unsigned()
                ->nullable();
            $table->foreign('awarded_demand_id')->references('id')
                ->on('demands')
                ->onDelete('cascade')
                ->onUpdate('cascade');

            $table->softDeletes();

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
        Schema::dropIfExists('awards');
    }
}

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCallCentersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('call_centers', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->smallInteger('call_center_award_type');
            $table->smallInteger('call_center_subproduct');

            $table->unsignedBigInteger('call_center_base_acesso_card_completo_id')->nullable();
            $table->foreign('call_center_base_acesso_card_completo_id')->on('base_acesso_cards_completo')
                ->references('id');


            $table->smallInteger('call_center_reason');
            $table->smallInteger('call_center_status');

            $table->string('call_center_phone');
            $table->string('call_center_email');
            $table->text('call_center_comments')->nullable();

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
        Schema::dropIfExists('call_centers');
    }
}

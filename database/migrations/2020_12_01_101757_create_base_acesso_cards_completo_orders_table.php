<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBaseAcessoCardsCompletoOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('base_acesso_cards_completo_orders', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('previous_card_id');
            $table->foreign('previous_card_id')->references('id')
                ->on('base_acesso_cards_completo');

            $table->unsignedBigInteger('currency_card_id')->nullable();
            $table->foreign('currency_card_id')->references('id')
                ->on('base_acesso_cards_completo');

            $table->unsignedBigInteger('call_center_id')->nullable();
            $table->foreign('call_center_id')->references('id')
                    ->on('call_centers');

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
        Schema::dropIfExists('base_acesso_cards_completo_orders');
    }
}

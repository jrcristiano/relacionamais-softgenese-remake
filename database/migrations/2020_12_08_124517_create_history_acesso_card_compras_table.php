<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateHistoryAcessoCardComprasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('history_acesso_card_compras', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->unsignedBigInteger('history_base_id');
            $table->foreign('history_base_id')->on('base_acesso_cards_compras')
                ->references('id');

            $table->unsignedBigInteger('history_acesso_card_id');
            $table->foreign('history_acesso_card_id')
                ->on('acesso_card_shoppings')
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
        Schema::dropIfExists('history_acesso_card_compras');
    }
}

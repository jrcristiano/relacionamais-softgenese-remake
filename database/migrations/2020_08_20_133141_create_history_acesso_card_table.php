<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateHistoryAcessoCardTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('history_acesso_card', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('history_base_id');

            $table->foreign('history_base_id')->on('base_acesso_cards_completo')
                ->references('id');

            $table->unsignedBigInteger('history_acesso_card_id');

            $table->foreign('history_acesso_card_id')
                ->on('acesso_cards')
                ->references('id');

            $table->decimal('history_acesso_card_value', 11, 4)
                ->unsigned();

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
        Schema::dropIfExists('history_acesso_card');
    }
}

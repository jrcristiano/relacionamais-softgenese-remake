<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBaseAcessoCardsCompras extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('base_acesso_cards_compras', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('base_acesso_card_id')->unique();
            $table->string('base_acesso_card_name')->nullable();

            $table->string('base_acesso_card_cpf')->nullable();

            $table->string('base_acesso_card_number')->nullable();
            $table->string('base_acesso_card_proxy')->unique();

            $table->date('base_acesso_card_due_date');

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
        Schema::dropIfExists('base_acesso_cards_compras');
    }
}

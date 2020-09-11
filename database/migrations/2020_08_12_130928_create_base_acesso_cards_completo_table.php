<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBaseAcessoCardsCompletoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('base_acesso_cards_completo', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('base_acesso_card_id');
            $table->string('base_acesso_card_name')->nullable();

            $table->string('base_acesso_card_cpf')->nullable()
                ->unique();

            $table->string('base_acesso_card_number')->unique();
            $table->string('base_acesso_card_proxy');
            $table->date('base_acesso_card_due_date');

            $table->boolean('base_acesso_card_generated')->nullable();

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
        Schema::dropIfExists('base_acesso_cards');
    }
}

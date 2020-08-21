<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAcessoCardsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('acesso_cards', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('acesso_card_name');
            $table->string('acesso_card_document')->unique();
            $table->decimal('acesso_card_value', 11, 4)->unsigned();

            $table->string('acesso_card_number')->nullable()
                ->unique();

            $table->unsignedBigInteger('acesso_card_spreadsheet_line');

            $table->boolean('acesso_card_chargeback')->nullable();

            $table->unsignedBigInteger('acesso_card_demand_id');
            $table->foreign('acesso_card_demand_id')->on('demands')
                ->references('id')
                ->onDelete('cascade')
                ->onUpdate('cascade');

            $table->unsignedBigInteger('acesso_card_award_id');
            $table->foreign('acesso_card_award_id')->references('id')
                ->on('awards');

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
        Schema::dropIfExists('acesso_cards');
    }
}

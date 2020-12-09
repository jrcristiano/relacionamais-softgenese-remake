<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAcessoCardShoppingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('acesso_card_shoppings', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->string('acesso_card_shopping_name');
            $table->string('acesso_card_shopping_document');
            $table->decimal('acesso_card_shopping_value', 11, 4)->unsigned();

            $table->string('acesso_card_shopping_number')->nullable();
            $table->string('acesso_card_shopping_proxy')->nullable();

            $table->unsignedBigInteger('acesso_card_shopping_spreadsheet_line');

            $table->boolean('acesso_card_shopping_chargeback')->nullable();
            $table->boolean('acesso_card_shopping_generated')->nullable();

            $table->unsignedBigInteger('acesso_card_shopping_demand_id');
            $table->foreign('acesso_card_shopping_demand_id')->on('demands')
                ->references('id')
                ->onDelete('cascade')
                ->onUpdate('cascade');

            $table->unsignedBigInteger('acesso_card_shopping_award_id');
            $table->foreign('acesso_card_shopping_award_id')->references('id')
                ->on('awards');

            $table->smallInteger('base_acesso_card_generated')->unsigned()
                ->nullable();

            $table->smallInteger('acesso_card_shopping_already_exists')->unsigned()
                ->nullable();

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
        Schema::dropIfExists('acesso_card_shoppings');
    }
}

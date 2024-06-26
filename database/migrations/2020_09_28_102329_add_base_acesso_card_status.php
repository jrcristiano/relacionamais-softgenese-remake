<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddBaseAcessoCardStatus extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('base_acesso_cards_completo', function (Blueprint $table) {
            $table->smallInteger('base_acesso_card_status')->unsigned()
                ->nullable()
                ->after('base_acesso_card_number');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('base_acesso_cards_completo', function (Blueprint $table) {
            //
        });
    }
}

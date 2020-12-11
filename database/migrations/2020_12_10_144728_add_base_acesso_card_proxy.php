<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddBaseAcessoCardProxy extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('base_acesso_cards_compras', function (Blueprint $table) {
            $table->string('base_acesso_card_proxy')->after('base_acesso_card_number');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('base_acesso_cards_compras', function (Blueprint $table) {
            //
        });
    }
}

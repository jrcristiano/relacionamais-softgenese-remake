<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddAcessoCardAlreadyExistsInAcessoCards extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('acesso_cards', function (Blueprint $table) {
            $table->boolean('acesso_card_already_exists')->nullable()
                ->after('acesso_card_proxy');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('acesso_cards', function (Blueprint $table) {
            //
        });
    }
}

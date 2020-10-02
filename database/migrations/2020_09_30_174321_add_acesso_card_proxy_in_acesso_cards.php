<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddAcessoCardProxyInAcessoCards extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('acesso_cards', function (Blueprint $table) {
            $table->string('acesso_card_proxy')->nullable()
                ->after('acesso_card_number');
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

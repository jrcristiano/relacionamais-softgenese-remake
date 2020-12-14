<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddBaseAcessoCardProxyGeneratedAndAlreadyExists extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('base_acesso_cards_compras', function (Blueprint $table) {

            if (!Schema::hasColumn('base_acesso_card_proxy', 'base_acesso_card_proxy')) {
                $table->string('base_acesso_card_proxy')->unique()
                    ->after('base_acesso_card_number');
            }

            $table->smallInteger('base_acesso_card_generated')->unsigned()
                ->nullable()
                ->after('base_acesso_card_due_date');

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
        Schema::table('base_acesso_cards_compras', function (Blueprint $table) {
            //
        });
    }
}

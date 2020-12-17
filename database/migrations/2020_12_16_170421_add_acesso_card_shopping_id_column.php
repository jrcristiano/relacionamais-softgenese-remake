<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddAcessoCardShoppingIdColumn extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('call_centers', function (Blueprint $table) {
            $table->unsignedBigInteger('call_center_acesso_card_shopping_id')->nullable()
                ->after('call_center_award_type');

            $table->foreign('call_center_acesso_card_shopping_id')->references('id')
                ->on('acesso_card_shoppings');

            $table->unsignedBigInteger('call_center_acesso_card_id')->nullable()
                ->after('id')
                ->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('call_centers', function (Blueprint $table) {
            //
        });
    }
}

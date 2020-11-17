<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterCallCenterBaseAcessoCardCompletoInCallCentersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('call_centers', function (Blueprint $table) {
            $table->dropForeign('call_centers_call_center_base_acesso_card_completo_id_foreign');
            $table->dropColumn('call_center_base_acesso_card_completo_id');
            $table->unsignedBigInteger('call_center_acesso_card_id')->after('id');
            $table->foreign('call_center_acesso_card_id')->references('id')
                ->on('acesso_cards');
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

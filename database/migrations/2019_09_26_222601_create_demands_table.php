<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class CreateDemandsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('demands', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('demand_client_cnpj', 18);
            $table->string('demand_client_name');
            $table->decimal('demand_prize_amount', 11, 4)->unsigned();
            $table->decimal('demand_taxable_amount', 11, 4)->unsigned();
            $table->decimal('demand_nfe_total', 11, 4)->unsigned()
                ->nullable();

            $table->softDeletes();

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
        DB::statement('SET FOREIGN_KEY_CHECKS = 0');
        Schema::dropIfExists('demands');
        DB::statement('SET FOREIGN_KEY_CHECKS = 1');
    }
}

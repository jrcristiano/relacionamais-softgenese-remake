<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddAlterTableDemandTaxableManual extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('demands', function (Blueprint $table) {
            $table->decimal('demand_taxable_manual', 11, 4)
                ->after('demand_taxable_amount')
                ->unsigned();
        });
    }
}

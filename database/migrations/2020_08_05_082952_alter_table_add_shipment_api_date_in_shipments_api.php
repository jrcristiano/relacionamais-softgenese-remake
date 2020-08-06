<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterTableAddShipmentApiDateInShipmentsApi extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('shipments_api', function (Blueprint $table) {
            $table->date('shipment_date')->default(date('Y-m-d'))
                ->after('shipment_file');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('shipments_api', function (Blueprint $table) {
            //
        });
    }
}

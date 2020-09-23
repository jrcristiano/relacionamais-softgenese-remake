<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddShipmentFileVinc extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('shipments_api', function (Blueprint $table) {
            $table->string('shipment_file_vinc')->after('shipment_file')
                ->nullable();
            $table->boolean('shipment_file_vinc_generated')
                ->after('shipment_file_vinc')
                ->nullable();
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

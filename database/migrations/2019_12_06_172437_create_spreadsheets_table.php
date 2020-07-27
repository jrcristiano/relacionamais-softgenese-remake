<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSpreadsheetsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('spreadsheets', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('spreadsheet_name');
            $table->string('spreadsheet_document');
            $table->decimal('spreadsheet_value', 11, 4);
            $table->string('spreadsheet_bank');
            $table->string('spreadsheet_agency');
            $table->string('spreadsheet_account');
            $table->string('spreadsheet_account_type');
            $table->integer('spreadsheet_keyline');
            $table->string('spreadsheet_shipment_file_path')->nullable();

            $table->boolean('spreadsheet_chargeback')->nullable();

            $table->unsignedBigInteger('spreadsheet_demand_id');
            $table->foreign('spreadsheet_demand_id')->on('demands')
                ->references('id')
                ->onDelete('cascade')
                ->onUpdate('cascade');

            $table->unsignedBigInteger('spreadsheet_award_id');
            $table->foreign('spreadsheet_award_id')->references('id')
                ->on('awards');

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
        Schema::dropIfExists('spreadsheets');
    }
}

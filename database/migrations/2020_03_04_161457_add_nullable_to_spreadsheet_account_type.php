<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddNullableToSpreadsheetAccountType extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('spreadsheets', function (Blueprint $table) {
            $table->string('spreadsheet_account_type')->nullable()
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
        Schema::table('spreadsheets', function (Blueprint $table) {
            Schema::drop('spreadsheets');
        });
    }
}

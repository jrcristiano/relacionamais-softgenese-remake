<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddNoteReceiptOtherValueInNoteReceipts extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('note_receipts', function (Blueprint $table) {
            $table->decimal('note_receipt_other_value', 11, 4)
                ->after('note_receipt_taxable_real_value')
                ->unsigned();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('note_receipts', function (Blueprint $table) {
            //
        });
    }
}

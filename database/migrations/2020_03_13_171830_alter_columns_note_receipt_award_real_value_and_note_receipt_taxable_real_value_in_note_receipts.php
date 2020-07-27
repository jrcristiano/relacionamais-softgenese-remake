<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterColumnsNoteReceiptAwardRealValueAndNoteReceiptTaxableRealValueInNoteReceipts extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('note_receipts', function (Blueprint $table) {
            $table->decimal('note_receipt_award_real_value', 11, 4)->nullable()
                ->change();
            $table->decimal('note_receipt_taxable_real_value', 11, 4)->nullable()
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
        Schema::table('note_receipts', function (Blueprint $table) {
            Schema::drop('note_receipts');
        });
    }
}

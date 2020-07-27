<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddNoteReceiptNoteInNoteReceipts extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('note_receipts', function (Blueprint $table) {
            $table->text('note_receipt_note')->nullable()
                ->after('note_receipt_id');
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

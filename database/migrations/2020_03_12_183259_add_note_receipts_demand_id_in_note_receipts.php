<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddNoteReceiptsDemandIdInNoteReceipts extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('note_receipts', function (Blueprint $table) {
            $table->unsignedBigInteger('note_receipt_demand_id');
            $table->foreign('note_receipt_demand_id')->references('id')
                ->on('demands')
                ->onDelete('cascade')
                ->onUpdate('cascade');
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

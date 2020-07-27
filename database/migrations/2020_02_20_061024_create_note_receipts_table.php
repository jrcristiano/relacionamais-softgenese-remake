<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateNoteReceiptsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('note_receipts', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->decimal('note_receipt_award_real_value', 11, 4);
            $table->decimal('note_receipt_taxable_real_value', 11, 4);

            $table->unsignedBigInteger('note_receipt_account_id');
            $table->foreign('note_receipt_account_id')->references('id')
                ->on('banks')
                ->onDelete('cascade')
                ->onUpdate('cascade');

            $table->date('note_receipt_date');

            $table->unsignedBigInteger('note_receipt_id')->nullable();
            $table->foreign('note_receipt_id')->references('id')
                ->on('notes')
                ->onDelete('cascade')
                ->onUpdate('cascade');

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
        Schema::dropIfExists('note_receipts');
    }
}

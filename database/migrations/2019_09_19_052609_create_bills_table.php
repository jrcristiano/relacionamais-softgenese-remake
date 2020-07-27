<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBillsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bills', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->decimal('bill_value', 11, 4);
            $table->date('bill_payday');
            $table->date('bill_due_date');
            $table->unsignedBigInteger('bill_bank_id');
            $table->foreign('bill_bank_id')->on('banks')
                ->references('id')
                ->onDelete('cascade')
                ->onUpdate('cascade');

            $table->smallInteger('bill_payment_status')->default(2);

            $table->unsignedBigInteger('bill_provider_id');
            $table->foreign('bill_provider_id')->references('id')
                ->on('providers');

            $table->longText('bill_note')->nullable();
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
        Schema::dropIfExists('bills');
    }
}

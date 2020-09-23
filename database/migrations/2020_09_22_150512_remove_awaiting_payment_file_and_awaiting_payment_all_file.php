<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RemoveAwaitingPaymentFileAndAwaitingPaymentAllFile extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('awaiting_payments', function (Blueprint $table) {
            $table->dropColumn('awaiting_payment_file');
            $table->dropColumn('awaiting_payment_all_file');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('awaiting_payments', function (Blueprint $table) {
            //
        });
    }
}

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CreateNotesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('notes', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('note_number')->unique();
            $table->smallInteger('note_status');
            $table->date('note_due_date'); // para criação
            $table->date('note_created_at')->default(date('Y-m-d'));

            $table->date('note_receipt_date')->nullable(); // para edição

            $table->unsignedBigInteger('note_account_receipt_id')->nullable();
            $table->foreign('note_account_receipt_id')->references('id')
                ->on('banks');

            $table->unsignedBigInteger('note_demand_id')->nullable()->unique();
            $table->foreign('note_demand_id')->references('id')
                ->on('demands');

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
        DB::statement('SET FOREIGN_KEY_CHECKS = 0');
        Schema::dropIfExists('notes');
        DB::statement('SET FOREIGN_KEY_CHECKS = 1');
    }
}

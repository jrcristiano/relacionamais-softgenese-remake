<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateClientsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('clients', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->string('client_company', 64);
            $table->string('client_address', 128);
            $table->string('client_phone', 16);
            $table->string('client_responsable_name', 64)->nullable();
            $table->string('client_cnpj', 18)->unique();
            $table->unsignedBigInteger('client_manager')->nullable();
            $table->float('client_rate_admin');
            $table->float('client_comission_manager');
            $table->string('client_state_reg')->nullable();

            $table->foreign('client_manager')->references('id')
                ->on('managers')
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
        Schema::dropIfExists('clients');
    }
}

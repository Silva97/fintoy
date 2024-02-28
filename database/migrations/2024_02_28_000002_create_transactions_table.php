<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTransactionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('payer_wallet_id');
            $table->unsignedBigInteger('payee_wallet_id');
            $table->unsignedInteger('value');
            $table->timestamp('transferred_at');

            $table->foreign('payer_wallet_id')
                ->references('id')
                ->on('users');

            $table->foreign('payee_wallet_id')
                ->references('id')
                ->on('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('transactions');
    }
}

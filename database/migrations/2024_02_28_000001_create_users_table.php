<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();

            $table->string('email', 320)->unique();
            $table->string('name', 255);
            $table->string('identification_number', 14)->unique();
            $table->boolean('is_shopkeeper');
            $table->unsignedBigInteger('wallet_id');
            $table->string('password', 60);

            $table->timestamps();

            $table->foreign('wallet_id')
                ->references('id')
                ->on('wallets');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
}

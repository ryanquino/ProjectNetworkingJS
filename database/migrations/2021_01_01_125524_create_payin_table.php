<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePayinTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('payins', function (Blueprint $table) {
            $table->id();
            $table->integer('userId');
            $table->double('amount');
            $table->date('dateApproved')->nullable();
            $table->tinyInteger('modeOfPayment')->nullable();
            $table->string('referenceNumber');
            $table->tinyInteger('status')->default('0');
            $table->integer('counter')->default('0');
            $table->timestamps();
            $table->foreign('userId')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('payin');
    }
}

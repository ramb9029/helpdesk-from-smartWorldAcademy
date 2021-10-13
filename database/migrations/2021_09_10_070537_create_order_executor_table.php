<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrderExecutorTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('order_executor', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->foreignId('order_id')
                ->constrained('orders')
                ->onDelete('cascade')->onUpdate('cascade');
            $table->foreignId('user_id')
                ->constrained('users')
                ->onDelete('cascade')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('order_executor');
    }
}

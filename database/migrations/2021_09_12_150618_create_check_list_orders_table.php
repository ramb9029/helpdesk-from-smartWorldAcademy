<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCheckListOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('check_list_orders', function (Blueprint $table) {
            $table->id();
            $table->string('description');
            $table->foreignId('order_id')
                ->constrained('orders')
                ->onDelete('cascade')->onUpdate('cascade');
            $table->boolean('status_complete')->default(false);
            //false - in progress true - complete
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
        Schema::dropIfExists('check_list_orders');
    }
}

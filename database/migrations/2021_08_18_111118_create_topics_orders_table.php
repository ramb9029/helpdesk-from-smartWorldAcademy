<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTopicsOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('order_topics', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->foreignId('order_id')
                ->constrained('orders')
                ->onDelete('cascade')->onUpdate('cascade');
            $table->foreignId('topic_id')
                ->constrained('topics')
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
        Schema::dropIfExists('topics_orders');
    }
}

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name');
            $table->foreignId('statusExecution_id')
                ->default('2')
                ->constrained('statuses_execution')
                ->onDelete('cascade')
                ->onUpdate('cascade');
            $table->foreignId('client_user_id')
                ->nullable()
                ->constrained('users')
                ->onDelete('cascade')
                ->onUpdate('cascade');
            $table->longText('description');
            $table->string('file')->nullable();
            $table->string('priority');
            $table->date('estimatedDueDate')->nullable();
            $table->boolean('access')->default(false);
            $table->string('action')->nullable();
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
        Schema::dropIfExists('orders');
    }
}

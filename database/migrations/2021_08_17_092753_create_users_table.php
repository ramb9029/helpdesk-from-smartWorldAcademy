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
            $table->BigIncrements('id');
            $table->string('firstName');
            $table->string('lastName');
            $table->string('middleName');
            $table->foreignId('role')->default(3)->constrained('roles')->onDelete('cascade')->onUpdate('cascade');
            $table->string('email')->unique();
            //$table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->foreignId('department_id')
                ->constrained('departments')
                ->onDelete('cascade')
                ->onUpdate('cascade');
            $table->foreignId('position_id')
                ->constrained('positions')
                ->onDelete('cascade')
                ->onUpdate('cascade');
            $table->foreignId('room_id')
                ->constrained('rooms')
                ->onDelete('cascade')
                ->onUpdate('cascade');
            $table->rememberToken()->default('Null');
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
        Schema::dropIfExists('users');
    }
}

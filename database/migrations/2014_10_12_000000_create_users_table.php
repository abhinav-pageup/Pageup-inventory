<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->smallInteger('id', true, true);
            $table->string('emp_id', 10)->nullable();
            $table->string('name', 50)->nullable();
            $table->string('email', 75)->unique()->nullable();
            $table->string('password');
            $table->string('phone', 10);
            $table->date('joined_at')->nullable();
            $table->boolean('is_active')->default(1);
            $table->boolean('is_admin')->default(0);
            $table->boolean('is_approve')->default(0);
            $table->smallInteger('created_by', false, true)->nullable();
            $table->smallInteger('updated_by', false, true)->nullable();
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
};

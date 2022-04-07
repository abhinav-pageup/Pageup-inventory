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
        Schema::create('product_master', function (Blueprint $table) {
            $table->tinyInteger('id', true, true);
            $table->string('name', 50)->nullable();
            $table->smallInteger('stock', false, true)->default(0);
            $table->enum('type', ['Household', 'Electronic']);
            $table->smallInteger('alloted', false, true)->default(0);
            $table->boolean('is_active')->default(1);
            $table->smallInteger('created_by', false, true)->nullable();
            $table->smallInteger('updated_by', false, true)->nullable();
            $table->timestamps();
            
            $table->foreign('updated_by')->references('id')->on('users');
            $table->foreign('created_by')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('product_master');
    }
};

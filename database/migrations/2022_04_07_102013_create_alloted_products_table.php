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
        Schema::create('alloted_products', function (Blueprint $table) {
            $table->smallInteger('id', true, true);
            $table->smallInteger('product_info_id', false, true)->nullable();
            $table->smallInteger('user_id', false, true)->nullable();
            $table->date('alloted_date')->useCurrent();
            $table->date('return_date')->nullable();
            $table->smallInteger('alloted_by', false, true)->nullable();
            $table->smallInteger('returned_to', false, true)->nullable();
            $table->timestamps();
            
            $table->foreign('product_info_id')->references('id')->on('product_info')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users');
            $table->foreign('alloted_by')->references('id')->on('users');
            $table->foreign('returned_to')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('alloted_products');
    }
};

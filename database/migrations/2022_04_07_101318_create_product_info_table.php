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
        Schema::create('product_info', function (Blueprint $table) {
            $table->smallInteger('id', true, true);
            $table->smallInteger('purchase_id', false, true)->nullable();
            $table->string('ref_no', 20)->unique()->nullable();
            $table->boolean('is_alloted')->default(0);
            $table->boolean('is_damage')->default(0);
            $table->boolean('is_active')->default(1);
            $table->timestamps();
            
            $table->foreign('purchase_id')->references('id')->on('purchases')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('product_info');
    }
};

<?php

use App\Models\ProductMaster;
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
        Schema::create('purchases', function (Blueprint $table) {
            $table->smallInteger('id', true, true);
            $table->tinyInteger('product_master_id', false, true)->nullable();
            $table->string('bill_no', 18)->unique()->nullable();
            $table->string('company', 25)->nullable();
            $table->tinyInteger('quantity', false, true)->nullable();
            $table->decimal('cost', 8, 2, true)->nullable();
            $table->boolean('is_active')->default(1);
            $table->date('date')->nullable();
            $table->smallInteger('created_by', false, true)->nullable();
            $table->smallInteger('updated_by', false, true)->nullable();
            $table->timestamps();
            
            $table->foreign('product_master_id')->references('id')->on('product_master');
            $table->foreign('created_by')->references('id')->on('users');
            $table->foreign('updated_by')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('purchases');
    }
};

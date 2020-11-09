<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProductExpiredDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('product_expired_details', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('product_expired_id');
            $table->bigInteger('product_purchase_detail_id')->nullable()->unsigned();
            $table->integer('product_category_id')->nullable();
            $table->integer('product_sub_category_id')->nullable();
            $table->integer('product_brand_id')->nullable();
            $table->bigInteger('product_id')->unsigned();
            $table->integer('qty')->nullable();
            $table->float('price',8,2)->nullable();
            $table->float('sub_total',8,2)->nullable();
            $table->string('barcode')->nullable();
            $table->timestamps();
            $table->foreign('product_purchase_detail_id')->references('id')->on('product_purchase_details')->onDelete('cascade');
            $table->foreign('product_id')->references('id')->on('products')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('product_expired_details');
    }
}

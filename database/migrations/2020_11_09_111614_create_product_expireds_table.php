<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProductExpiredsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('product_expireds', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('invoice_no')->nullable();
            $table->integer('user_id');
            $table->bigInteger('store_id')->unsigned();
            $table->bigInteger('product_purchase_id')->nullable()->unsigned();
            $table->bigInteger('product_purchase_detail_id')->nullable()->unsigned();
            $table->integer('product_category_id')->nullable();
            $table->integer('product_sub_category_id')->nullable();
            $table->integer('product_brand_id')->nullable();
            $table->bigInteger('product_id')->unsigned();
            $table->integer('qty')->nullable();
            $table->float('price',8,2)->nullable();
            $table->float('total',8,2)->nullable();
            $table->string('barcode')->nullable();
            $table->timestamps();
            $table->foreign('store_id')->references('id')->on('stores')->onDelete('cascade');
            $table->foreign('product_purchase_id')->references('id')->on('product_purchases')->onDelete('cascade');
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
        Schema::dropIfExists('product_expireds');
    }
}

<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProductSaleReturnsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('product_sale_returns', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('invoice_no');
            $table->string('sale_invoice_no');
            $table->bigInteger('product_sale_id')->unsigned();
            $table->integer('user_id');
            $table->bigInteger('store_id')->unsigned();
            $table->bigInteger('party_id')->unsigned();
            $table->enum('payment_type', ['cash','online']);
            $table->enum('discount_type',['flat','percentage']);
            $table->float('discount_amount', 8,2);
            $table->float('total_amount', 8,2);
            $table->timestamps();
            $table->foreign('product_sale_id')->references('id')->on('product_sales')->onDelete('cascade');
            $table->foreign('store_id')->references('id')->on('stores')->onDelete('cascade');
            $table->foreign('party_id')->references('id')->on('parties')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('product_sale_returns');
    }
}

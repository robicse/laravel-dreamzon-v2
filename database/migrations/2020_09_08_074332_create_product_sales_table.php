<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProductSalesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('product_sales', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('invoice_no');
            $table->integer('user_id');
            $table->bigInteger('store_id')->unsigned();
            $table->bigInteger('party_id')->unsigned();
            $table->enum('payment_type', ['cash','online']);
            $table->string('delivery_service');
            $table->string('delivery_service_charge');
            $table->enum('discount_type',['flat','percentage']);
            $table->float('discount_amount', 8,2);
            $table->float('total_amount', 8,2);
            $table->float('paid_amount', 8,2);
            $table->float('due_amount', 8,2);
            $table->timestamps();
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
        Schema::dropIfExists('product_sales');
    }
}

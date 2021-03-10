<?php

//    function changeDateFormate($date,$date_format){
//        return \Carbon\Carbon::createFromFormat('Y-m-d', $date)->format($date_format);
//    }
//
//    function productImagePath($image_name)
//    {
//        return public_path('images/products/'.$image_name);
//    }

    function store_test($store_id){
        return $store_id + 3;
    }

    function sum_loss_or_profit($store_id){
        $sum_loss_or_profit = 0;
        $productSaleDetails = DB::table('product_sale_details')
            ->join('product_sales', 'product_sale_details.product_sale_id', '=', 'product_sales.id')
            ->select(
                'product_sale_details.product_id',
                DB::raw('SUM(qty) as qty'),
                DB::raw('SUM(price) as price'),
                DB::raw('SUM(sub_total) as sub_total')
            )
            ->where('product_sales.store_id', $store_id)
            ->groupBy('product_sale_details.product_id')
            ->get();

        if(!empty($productSaleDetails)) {
            foreach ($productSaleDetails as $productSaleDetail){
                $product_id = $productSaleDetail->product_id;
                $sale_total_qty = $productSaleDetail->qty;
                //$sale_price = $productSaleDetail->price;
                $sale_sub_total = $productSaleDetail->sub_total;
                $sale_average_price = $sale_sub_total/$sale_total_qty;

                $productPurchasesSummation = DB::table('product_purchase_details')
                    ->join('product_purchases','product_purchases.id','=','product_purchase_details.product_purchase_id')
                    ->select(
                        'product_purchase_details.product_id',
                        DB::raw('SUM(product_purchase_details.qty) as qty'),
                        DB::raw('SUM(product_purchase_details.price) as price'),
                        DB::raw('SUM(product_purchase_details.sub_total) as sub_total')
                    )
                    ->where('product_purchases.store_id',$store_id)
                    ->where('product_purchase_details.product_id',$product_id)
                    ->groupBy('product_purchase_details.product_id')
                    ->first();

                if(!empty($productPurchasesSummation)){

                    //$purchase_product_id = $productPurchasesSummation->product_id;
                    $purchase_total_qty = $productPurchasesSummation->qty;
                    //$purchase_price = $productPurchasesSummation->price;
                    $purchase_sub_total = $productPurchasesSummation->sub_total;

                    $purchase_average_price = $purchase_sub_total/$purchase_total_qty;

                    $loss_or_profit = ($sale_average_price*$sale_total_qty) - ($purchase_average_price*$sale_total_qty);
                    $sum_loss_or_profit += $loss_or_profit;

                }
            }

        }

        return $sum_loss_or_profit;
    }

    function sum_last_thirty_day_loss_or_profit($store_id){
        $sum_loss_or_profit = 0;
        $productSaleDetails = DB::table('product_sale_details')
            ->join('product_sales', 'product_sale_details.product_sale_id', '=', 'product_sales.id')
            ->select(
                'product_sale_details.product_id',
                DB::raw('SUM(qty) as qty'),
                DB::raw('SUM(price) as price'),
                DB::raw('SUM(sub_total) as sub_total')
            )
            ->where('product_sales.store_id', $store_id)
            ->where('product_sale_details.created_at','>=',date('Y-m-d',strtotime('-30 days')).' 00:00:00')
            ->where('product_sale_details.created_at','<=',date('Y-m-d').' 23:59:59')
            ->groupBy('product_sale_details.product_id')
            ->get();

        if(!empty($productSaleDetails)) {
            foreach ($productSaleDetails as $productSaleDetail){
                $product_id = $productSaleDetail->product_id;
                $sale_total_qty = $productSaleDetail->qty;
                //$sale_price = $productSaleDetail->price;
                $sale_sub_total = $productSaleDetail->sub_total;
                $sale_average_price = $sale_sub_total/$sale_total_qty;

                $productPurchasesSummation = DB::table('product_purchase_details')
                    ->join('product_purchases','product_purchases.id','=','product_purchase_details.product_purchase_id')
                    ->select(
                        'product_purchase_details.product_id',
                        DB::raw('SUM(product_purchase_details.qty) as qty'),
                        DB::raw('SUM(product_purchase_details.price) as price'),
                        DB::raw('SUM(product_purchase_details.sub_total) as sub_total')
                    )
                    ->where('product_purchases.store_id',$store_id)
                    ->where('product_purchase_details.product_id',$product_id)
                    ->groupBy('product_purchase_details.product_id')
                    ->first();

                if(!empty($productPurchasesSummation)){

                    //$purchase_product_id = $productPurchasesSummation->product_id;
                    $purchase_total_qty = $productPurchasesSummation->qty;
                    //$purchase_price = $productPurchasesSummation->price;
                    $purchase_sub_total = $productPurchasesSummation->sub_total;

                    $purchase_average_price = $purchase_sub_total/$purchase_total_qty;

                    $loss_or_profit = ($sale_average_price*$sale_total_qty) - ($purchase_average_price*$sale_total_qty);
                    $sum_loss_or_profit += $loss_or_profit;

                }
            }

        }

        return $sum_loss_or_profit;
    }

    function sum_today_loss_or_profit($store_id){
        $sum_loss_or_profit = 0;
        $productSaleDetails = DB::table('product_sale_details')
            ->join('product_sales', 'product_sale_details.product_sale_id', '=', 'product_sales.id')
            ->select(
                'product_sale_details.product_id',
                DB::raw('SUM(qty) as qty'),
                DB::raw('SUM(price) as price'),
                DB::raw('SUM(sub_total) as sub_total')
            )
            ->where('product_sales.store_id', $store_id)
            ->where('product_sale_details.created_at','>=',date('Y-m-d').' 00:00:00')
            ->where('product_sale_details.created_at','<=',date('Y-m-d').' 23:59:59')
            ->groupBy('product_sale_details.product_id')
            ->get();

        if(!empty($productSaleDetails)) {
            foreach ($productSaleDetails as $productSaleDetail){
                $product_id = $productSaleDetail->product_id;
                $sale_total_qty = $productSaleDetail->qty;
                //$sale_price = $productSaleDetail->price;
                $sale_sub_total = $productSaleDetail->sub_total;
                $sale_average_price = $sale_sub_total/$sale_total_qty;

                $productPurchasesSummation = DB::table('product_purchase_details')
                    ->join('product_purchases','product_purchases.id','=','product_purchase_details.product_purchase_id')
                    ->select(
                        'product_purchase_details.product_id',
                        DB::raw('SUM(product_purchase_details.qty) as qty'),
                        DB::raw('SUM(product_purchase_details.price) as price'),
                        DB::raw('SUM(product_purchase_details.sub_total) as sub_total')
                    )
                    ->where('product_purchases.store_id',$store_id)
                    ->where('product_purchase_details.product_id',$product_id)
                    ->groupBy('product_purchase_details.product_id')
                    ->first();

                if(!empty($productPurchasesSummation)){

                    //$purchase_product_id = $productPurchasesSummation->product_id;
                    $purchase_total_qty = $productPurchasesSummation->qty;
                    //$purchase_price = $productPurchasesSummation->price;
                    $purchase_sub_total = $productPurchasesSummation->sub_total;

                    $purchase_average_price = $purchase_sub_total/$purchase_total_qty;

                    $loss_or_profit = ($sale_average_price*$sale_total_qty) - ($purchase_average_price*$sale_total_qty);
                    $sum_loss_or_profit += $loss_or_profit;

                }
            }

        }

        return $sum_loss_or_profit;
    }

    function sum_stock_purchase_price($store_id){
        $transactions = \App\Transaction::where('store_id',$store_id)->where('transaction_type','purchase')->get();
        $sum_amount = 0;
        foreach($transactions as $key => $transaction){
            $sum_amount += $transaction->amount;
        }

        $sum_stock_purchase_price = $sum_amount;

        $productPurchasesSummations = DB::table('product_purchase_details')
            ->join('product_purchases','product_purchases.id','=','product_purchase_details.product_purchase_id')
            ->select(
                'product_purchase_details.product_id',
                'product_purchase_details.product_category_id',
                'product_purchase_details.product_sub_category_id',
                'product_purchase_details.product_brand_id',
                DB::raw('SUM(product_purchase_details.qty) as qty'),
                DB::raw('SUM(product_purchase_details.price) as price'),
                DB::raw('SUM(product_purchase_details.sub_total) as sub_total')
            )
            ->where('product_purchases.store_id',$store_id)
            ->groupBy('product_purchase_details.product_id')
            ->groupBy('product_purchase_details.product_category_id')
            ->groupBy('product_purchase_details.product_sub_category_id')
            ->groupBy('product_purchase_details.product_brand_id')
            ->get();




        if(!empty($productPurchasesSummations)){
            foreach($productPurchasesSummations as $key => $productPurchasesSummation){
                $purchase_average_price = $productPurchasesSummation->sub_total/$productPurchasesSummation->qty;


                // sale total
                $productSaleDetails = DB::table('product_sale_details')
                    ->join('product_sales','product_sale_details.product_sale_id','=','product_sales.id')
                    ->select('product_sale_details.product_id','product_sale_details.product_category_id','product_sale_details.product_sub_category_id','product_sale_details.product_brand_id', DB::raw('SUM(qty) as qty'), DB::raw('SUM(price) as price'), DB::raw('SUM(sub_total) as sub_total'))
                    ->where('product_sale_details.product_id',$productPurchasesSummation->product_id)
                    ->where('product_sale_details.product_category_id',$productPurchasesSummation->product_category_id)
                    ->where('product_sale_details.product_sub_category_id',$productPurchasesSummation->product_sub_category_id)
                    ->where('product_sale_details.product_brand_id',$productPurchasesSummation->product_brand_id)
                    ->where('product_sales.store_id',$store_id)
                    ->groupBy('product_sale_details.product_id')
                    ->groupBy('product_sale_details.product_category_id')
                    ->groupBy('product_sale_details.product_sub_category_id')
                    ->groupBy('product_sale_details.product_brand_id')
                    ->first();

                if(!empty($productSaleDetails))
                {
                    $sale_total_qty = $productSaleDetails->qty;

                    //$last_qty = $purchase_total_qty - $sale_total_qty;

                    $sum_stock_purchase_price -= $purchase_average_price*$sale_total_qty;

                }
            }
        }
        return $sum_stock_purchase_price;
    }

    function from_stock_transfer_sum_sub_total($store_id){
        $from_stock_transfer_sum_sub_total = 0;

        $fromStockTransferDetails = DB::table('stock_transfer_details')
            ->join('stock_transfers','stock_transfers.id','=','stock_transfer_details.stock_transfer_id')
            ->select(DB::raw('SUM(stock_transfer_details.sub_total) as from_stock_transfer_sum_sub_total'))
            ->where('stock_transfers.from_store_id',$store_id)
            ->first();

        if($fromStockTransferDetails){
            $from_stock_transfer_sum_sub_total = $fromStockTransferDetails->from_stock_transfer_sum_sub_total;
        }

        return $from_stock_transfer_sum_sub_total;
    }

function to_stock_transfer_sum_sub_total($store_id){
    $to_stock_transfer_sum_sub_total = 0;

    $toStockTransferDetails = DB::table('stock_transfer_details')
        ->join('stock_transfers','stock_transfers.id','=','stock_transfer_details.stock_transfer_id')
        ->select(DB::raw('SUM(stock_transfer_details.sub_total) as to_stock_transfer_sum_sub_total'))
        ->where('stock_transfers.to_store_id',$store_id)
        ->first();

    if($toStockTransferDetails){
        $to_stock_transfer_sum_sub_total = $toStockTransferDetails->to_stock_transfer_sum_sub_total;
    }

    return $to_stock_transfer_sum_sub_total;
}

?>

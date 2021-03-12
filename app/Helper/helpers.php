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
//    $sum_loss_or_profit = 0;
//    $productSaleDetails = DB::table('product_sale_details')
//        ->join('product_sales', 'product_sale_details.product_sale_id', '=', 'product_sales.id')
//        ->select(
//            'product_sale_details.product_id',
//            DB::raw('SUM(qty) as qty'),
//            DB::raw('SUM(price) as price'),
//            DB::raw('SUM(sub_total) as sub_total')
//        )
//        ->where('product_sales.store_id', $store_id)
//        ->groupBy('product_sale_details.product_id')
//        ->get();
//
//    if(!empty($productSaleDetails)) {
//        foreach ($productSaleDetails as $productSaleDetail){
//            $product_id = $productSaleDetail->product_id;
//            $sale_total_qty = $productSaleDetail->qty;
//            $sale_sub_total = $productSaleDetail->sub_total;
//
//            $productPurchasesSummation = DB::table('product_purchase_details')
//                ->join('product_purchases','product_purchases.id','=','product_purchase_details.product_purchase_id')
//                ->select(
//                    'product_purchase_details.product_id',
//                    DB::raw('SUM(product_purchase_details.qty) as qty'),
//                    DB::raw('SUM(product_purchase_details.price) as price'),
//                    DB::raw('SUM(product_purchase_details.sub_total) as sub_total')
//                )
//                ->where('product_purchases.store_id',$store_id)
//                ->where('product_purchase_details.product_id',$product_id)
//                ->groupBy('product_purchase_details.product_id')
//                ->first();
//
//            if(!empty($productPurchasesSummation)){
//
//                $purchase_total_qty = $productPurchasesSummation->qty;
//                $purchase_sub_total = $productPurchasesSummation->sub_total;
//                $purchase_average_price = $purchase_sub_total/$purchase_total_qty;
//
//                $loss_or_profit = $sale_sub_total - ($purchase_average_price*$sale_total_qty);
//                $sum_loss_or_profit += $loss_or_profit;
//
//            }
//        }
//
//    }
//
//    return $sum_loss_or_profit;

    $productPurchaseDetails = DB::table('product_purchase_details')
        ->join('product_purchases','product_purchases.id','=','product_purchase_details.product_purchase_id')
        ->select('product_id','product_category_id','product_sub_category_id','product_brand_id', DB::raw('SUM(qty) as qty'), DB::raw('SUM(price) as price'), DB::raw('SUM(sub_total) as sub_total'))
        ->where('product_purchases.store_id',$store_id)
        ->groupBy('product_id')
        ->groupBy('product_category_id')
        ->groupBy('product_sub_category_id')
        ->groupBy('product_brand_id')
        ->get();

    $sum_loss_or_profit = 0;

    foreach($productPurchaseDetails as $key => $productPurchaseDetail){


        $productPurchaseDetail->qty;
        $productPurchaseDetail->sub_total;
        $purchase_average_price = $productPurchaseDetail->sub_total/$productPurchaseDetail->qty;

        $productSaleDetails = DB::table('product_sale_details')
            ->join('product_sales','product_sale_details.product_sale_id','=','product_sales.id')
            ->select('product_sale_details.product_id','product_sale_details.product_category_id','product_sale_details.product_sub_category_id','product_sale_details.product_brand_id', DB::raw('SUM(qty) as qty'), DB::raw('SUM(price) as price'), DB::raw('SUM(sub_total) as sub_total'))
            ->where('product_sale_details.product_id',$productPurchaseDetail->product_id)
            ->where('product_sale_details.product_category_id',$productPurchaseDetail->product_category_id)
            ->where('product_sale_details.product_sub_category_id',$productPurchaseDetail->product_sub_category_id)
            ->where('product_sale_details.product_brand_id',$productPurchaseDetail->product_brand_id)
            ->where('product_sales.store_id',$store_id)
            ->groupBy('product_sale_details.product_id')
            ->groupBy('product_sale_details.product_category_id')
            ->groupBy('product_sale_details.product_sub_category_id')
            ->groupBy('product_sale_details.product_brand_id')
            ->first();

        if(!empty($productSaleDetails))
        {
            $sale_total_qty = $productSaleDetails->qty;
            $sale_total_sub_total = $productSaleDetails->sub_total;
            $sale_average_price = $productSaleDetails->sub_total/$productSaleDetails->qty;

            if($sale_total_qty > 0){
                //$loss_or_profit = ($sale_average_price*$sale_total_qty) - ($purchase_average_price*$sale_total_qty);
                $loss_or_profit = $sale_total_sub_total - ($purchase_average_price*$sale_total_qty);
                $sum_loss_or_profit += $loss_or_profit;
            }
        }


        $productSaleReturnDetails = DB::table('product_sale_return_details')
            ->select('product_id',
                'product_category_id',
                'product_sub_category_id',
                'product_brand_id',
                DB::raw('SUM(qty) as qty'),
                DB::raw('SUM(price) as price'))
            ->where('product_id',$productPurchaseDetail->product_id)
            ->where('product_category_id',$productPurchaseDetail->product_category_id)
            ->where('product_sub_category_id',$productPurchaseDetail->product_sub_category_id)
            ->where('product_brand_id',$productPurchaseDetail->product_brand_id)
            ->groupBy('product_id')
            ->groupBy('product_category_id')
            ->groupBy('product_sub_category_id')
            ->groupBy('product_brand_id')
            ->first();


        if(!empty($productSaleReturnDetails))
        {
            $sale_return_total_qty = $productSaleReturnDetails->qty;
            $sale_return_total_amount = $productSaleReturnDetails->price;
            $sale_return_average_price = $sale_return_total_amount/$productSaleReturnDetails->qty;

            if($sale_return_total_qty > 0){
                //$loss_or_profit = $sale_return_average_price - ($purchase_average_price*$sale_return_total_qty);
                $loss_or_profit = ($sale_return_total_qty*$sale_return_total_amount) - ($purchase_average_price*$sale_return_total_qty);
                $sum_loss_or_profit -= $loss_or_profit;
            }
        }

    }


    $productSaleDiscount = DB::table('product_sales')
        ->select( DB::raw('SUM(discount_amount) as total_discount'))
        ->where('store_id',$store_id)
        ->first();

    $sum_total_discount = 0;

    if($productSaleDiscount){
        $sum_total_discount = $productSaleDiscount->total_discount;
    }


    return $sum_loss_or_profit - $sum_total_discount;

}

    function sum_last_thirty_day_loss_or_profit($store_id){
//        $sum_loss_or_profit = 0;
//        $productSaleDetails = DB::table('product_sale_details')
//            ->join('product_sales', 'product_sale_details.product_sale_id', '=', 'product_sales.id')
//            ->select(
//                'product_sale_details.product_id',
//                DB::raw('SUM(qty) as qty'),
//                DB::raw('SUM(price) as price'),
//                DB::raw('SUM(sub_total) as sub_total')
//            )
//            ->where('product_sales.store_id', $store_id)
//            ->where('product_sale_details.created_at','>=',date('Y-m-d',strtotime('-30 days')).' 00:00:00')
//            ->where('product_sale_details.created_at','<=',date('Y-m-d').' 23:59:59')
//            ->groupBy('product_sale_details.product_id')
//            ->get();
//
//        if(!empty($productSaleDetails)) {
//            foreach ($productSaleDetails as $productSaleDetail){
//                $product_id = $productSaleDetail->product_id;
//                $sale_total_qty = $productSaleDetail->qty;
//                $sale_sub_total = $productSaleDetail->sub_total;
//
//                $productPurchasesSummation = DB::table('product_purchase_details')
//                    ->join('product_purchases','product_purchases.id','=','product_purchase_details.product_purchase_id')
//                    ->select(
//                        'product_purchase_details.product_id',
//                        DB::raw('SUM(product_purchase_details.qty) as qty'),
//                        DB::raw('SUM(product_purchase_details.price) as price'),
//                        DB::raw('SUM(product_purchase_details.sub_total) as sub_total')
//                    )
//                    ->where('product_purchases.store_id',$store_id)
//                    ->where('product_purchase_details.product_id',$product_id)
//                    ->groupBy('product_purchase_details.product_id')
//                    ->first();
//
//                if(!empty($productPurchasesSummation)){
//
//                    $purchase_total_qty = $productPurchasesSummation->qty;
//                    $purchase_sub_total = $productPurchasesSummation->sub_total;
//                    $purchase_average_price = $purchase_sub_total/$purchase_total_qty;
//
//                    //$loss_or_profit = ($sale_average_price*$sale_total_qty) - ($purchase_average_price*$sale_total_qty);
//                    $loss_or_profit = $sale_sub_total - ($purchase_average_price*$sale_total_qty);
//                    $sum_loss_or_profit += $loss_or_profit;
//
//                }
//            }
//
//        }
//
//        return $sum_loss_or_profit;

        $productPurchaseDetails = DB::table('product_purchase_details')
            ->join('product_purchases','product_purchases.id','=','product_purchase_details.product_purchase_id')
            ->select('product_id','product_category_id','product_sub_category_id','product_brand_id', DB::raw('SUM(qty) as qty'), DB::raw('SUM(price) as price'), DB::raw('SUM(sub_total) as sub_total'))
            ->where('product_purchases.store_id',$store_id)
            //->where('product_purchase_details.created_at','>=',date('Y-m-d',strtotime('-30 days')).' 00:00:00')
            //->where('product_purchase_details.created_at','<=',date('Y-m-d').' 23:59:59')
            ->groupBy('product_id')
            ->groupBy('product_category_id')
            ->groupBy('product_sub_category_id')
            ->groupBy('product_brand_id')
            ->get();

        $sum_loss_or_profit = 0;

        foreach($productPurchaseDetails as $key => $productPurchaseDetail){


            $productPurchaseDetail->qty;
            $productPurchaseDetail->sub_total;
            $purchase_average_price = $productPurchaseDetail->sub_total/$productPurchaseDetail->qty;

            $productSaleDetails = DB::table('product_sale_details')
                ->join('product_sales','product_sale_details.product_sale_id','=','product_sales.id')
                ->select('product_sale_details.product_id','product_sale_details.product_category_id','product_sale_details.product_sub_category_id','product_sale_details.product_brand_id', DB::raw('SUM(qty) as qty'), DB::raw('SUM(price) as price'), DB::raw('SUM(sub_total) as sub_total'))
                ->where('product_sale_details.product_id',$productPurchaseDetail->product_id)
                ->where('product_sale_details.product_category_id',$productPurchaseDetail->product_category_id)
                ->where('product_sale_details.product_sub_category_id',$productPurchaseDetail->product_sub_category_id)
                ->where('product_sale_details.product_brand_id',$productPurchaseDetail->product_brand_id)
                ->where('product_sales.store_id',$store_id)
                ->where('product_sale_details.created_at','>=',date('Y-m-d',strtotime('-30 days')).' 00:00:00')
                ->where('product_sale_details.created_at','<=',date('Y-m-d').' 23:59:59')
                ->groupBy('product_sale_details.product_id')
                ->groupBy('product_sale_details.product_category_id')
                ->groupBy('product_sale_details.product_sub_category_id')
                ->groupBy('product_sale_details.product_brand_id')
                ->first();

            if(!empty($productSaleDetails))
            {
                $sale_total_qty = $productSaleDetails->qty;
                $sale_total_sub_total = $productSaleDetails->sub_total;
                $sale_average_price = $productSaleDetails->sub_total/$productSaleDetails->qty;

                if($sale_total_qty > 0){
                    //$loss_or_profit = ($sale_average_price*$sale_total_qty) - ($purchase_average_price*$sale_total_qty);
                    $loss_or_profit = $sale_total_sub_total - ($purchase_average_price*$sale_total_qty);
                    $sum_loss_or_profit += $loss_or_profit;
                }
            }


            $productSaleReturnDetails = DB::table('product_sale_return_details')
                ->select('product_id','product_category_id','product_sub_category_id','product_brand_id', DB::raw('SUM(qty) as qty'), DB::raw('SUM(price) as price'))
                ->where('product_id',$productPurchaseDetail->product_id)
                ->where('product_category_id',$productPurchaseDetail->product_category_id)
                ->where('product_sub_category_id',$productPurchaseDetail->product_sub_category_id)
                ->where('product_brand_id',$productPurchaseDetail->product_brand_id)
                //->where('product_sale_return_details.created_at','>=',date('Y-m-d',strtotime('-30 days')).' 00:00:00')
                //->where('product_sale_return_details.created_at','<=',date('Y-m-d').' 23:59:59')
                ->groupBy('product_id')
                ->groupBy('product_category_id')
                ->groupBy('product_sub_category_id')
                ->groupBy('product_brand_id')
                ->first();


            if(!empty($productSaleReturnDetails))
            {
                $sale_return_total_qty = $productSaleReturnDetails->qty;
                $sale_return_total_amount = $productSaleReturnDetails->price;
                $sale_return_average_price = $sale_return_total_amount/$productSaleReturnDetails->qty;

                if($sale_return_total_qty > 0){
                    //$loss_or_profit = $sale_return_average_price - ($purchase_average_price*$sale_return_total_qty);
                    $loss_or_profit = ($sale_return_total_qty*$sale_return_total_amount) - ($purchase_average_price*$sale_return_total_qty);
                    $sum_loss_or_profit -= $loss_or_profit;
                }
            }

        }

        $productSaleDiscount = DB::table('product_sales')
            ->select( DB::raw('SUM(discount_amount) as total_discount'))
            ->where('store_id',$store_id)
            ->where('created_at','>=',date('Y-m-d',strtotime('-30 days')).' 00:00:00')
            ->where('created_at','<=',date('Y-m-d').' 23:59:59')
            ->first();

        $sum_total_discount = 0;
        if($productSaleDiscount){
            $sum_total_discount = $productSaleDiscount->total_discount;
        }

        return $sum_loss_or_profit - $sum_total_discount;
    }

    function sum_today_loss_or_profit($store_id){
//        $sum_loss_or_profit = 0;
//        $productSaleDetails = DB::table('product_sale_details')
//            ->join('product_sales', 'product_sale_details.product_sale_id', '=', 'product_sales.id')
//            ->select(
//                'product_sale_details.product_id',
//                DB::raw('SUM(qty) as qty'),
//                DB::raw('SUM(price) as price'),
//                DB::raw('SUM(sub_total) as sub_total')
//            )
//            ->where('product_sales.store_id', $store_id)
//            ->where('product_sale_details.created_at','>=',date('Y-m-d').' 00:00:00')
//            ->where('product_sale_details.created_at','<=',date('Y-m-d').' 23:59:59')
//            ->groupBy('product_sale_details.product_id')
//            ->get();
//
//        if(!empty($productSaleDetails)) {
//            foreach ($productSaleDetails as $productSaleDetail){
//                $product_id = $productSaleDetail->product_id;
//                $sale_total_qty = $productSaleDetail->qty;
//                $sale_sub_total = $productSaleDetail->sub_total;
//
//                $productPurchasesSummation = DB::table('product_purchase_details')
//                    ->join('product_purchases','product_purchases.id','=','product_purchase_details.product_purchase_id')
//                    ->select(
//                        'product_purchase_details.product_id',
//                        DB::raw('SUM(product_purchase_details.qty) as qty'),
//                        DB::raw('SUM(product_purchase_details.price) as price'),
//                        DB::raw('SUM(product_purchase_details.sub_total) as sub_total')
//                    )
//                    ->where('product_purchases.store_id',$store_id)
//                    ->where('product_purchase_details.product_id',$product_id)
//                    ->groupBy('product_purchase_details.product_id')
//                    ->first();
//
//                if(!empty($productPurchasesSummation)){
//
//                    $purchase_total_qty = $productPurchasesSummation->qty;
//                    $purchase_sub_total = $productPurchasesSummation->sub_total;
//                    $purchase_average_price = $purchase_sub_total/$purchase_total_qty;
//
//                    //$loss_or_profit = ($sale_average_price*$sale_total_qty) - ($purchase_average_price*$sale_total_qty);
//                    $loss_or_profit = $sale_sub_total - ($purchase_average_price*$sale_total_qty);
//                    $sum_loss_or_profit += $loss_or_profit;
//                }
//            }
//
//        }
//
//        return $sum_loss_or_profit;

        $productPurchaseDetails = DB::table('product_purchase_details')
            ->join('product_purchases','product_purchases.id','=','product_purchase_details.product_purchase_id')
            ->select('product_id','product_category_id','product_sub_category_id','product_brand_id', DB::raw('SUM(qty) as qty'), DB::raw('SUM(price) as price'), DB::raw('SUM(sub_total) as sub_total'))
            ->where('product_purchases.store_id',$store_id)
            //->where('product_purchase_details.created_at','>=',date('Y-m-d').' 00:00:00')
            //->where('product_purchase_details.created_at','<=',date('Y-m-d').' 23:59:59')
            ->groupBy('product_id')
            ->groupBy('product_category_id')
            ->groupBy('product_sub_category_id')
            ->groupBy('product_brand_id')
            ->get();

        $sum_loss_or_profit = 0;

        foreach($productPurchaseDetails as $key => $productPurchaseDetail){


            $productPurchaseDetail->qty;
            $productPurchaseDetail->sub_total;
            $purchase_average_price = $productPurchaseDetail->sub_total/$productPurchaseDetail->qty;

            $productSaleDetails = DB::table('product_sale_details')
                ->join('product_sales','product_sale_details.product_sale_id','=','product_sales.id')
                ->select(
                    'product_sale_details.product_id',
                    'product_sale_details.product_category_id',
                    'product_sale_details.product_sub_category_id',
                    'product_sale_details.product_brand_id',
                    DB::raw('SUM(qty) as qty'),
                    DB::raw('SUM(price) as price'),
                    DB::raw('SUM(sub_total) as sub_total')
                )
                ->where('product_sale_details.product_id',$productPurchaseDetail->product_id)
                ->where('product_sale_details.product_category_id',$productPurchaseDetail->product_category_id)
                ->where('product_sale_details.product_sub_category_id',$productPurchaseDetail->product_sub_category_id)
                ->where('product_sale_details.product_brand_id',$productPurchaseDetail->product_brand_id)
                ->where('product_sales.store_id',$store_id)
                ->where('product_sale_details.created_at','>=',date('Y-m-d').' 00:00:00')
                ->where('product_sale_details.created_at','<=',date('Y-m-d').' 23:59:59')
                ->groupBy('product_sale_details.product_id')
                ->groupBy('product_sale_details.product_category_id')
                ->groupBy('product_sale_details.product_sub_category_id')
                ->groupBy('product_sale_details.product_brand_id')
                ->first();

            if(!empty($productSaleDetails))
            {
                $sale_total_qty = $productSaleDetails->qty;
                $sale_total_sub_total = $productSaleDetails->sub_total;
                $sale_average_price = $productSaleDetails->sub_total/$productSaleDetails->qty;

                if($sale_total_qty > 0){
                    //$loss_or_profit = ($sale_average_price*$sale_total_qty) - ($purchase_average_price*$sale_total_qty);
                    $loss_or_profit = $sale_total_sub_total - ($purchase_average_price*$sale_total_qty);
                    $sum_loss_or_profit += $loss_or_profit;
                }
            }


            $productSaleReturnDetails = DB::table('product_sale_return_details')
                ->select('product_id','product_category_id','product_sub_category_id','product_brand_id', DB::raw('SUM(qty) as qty'), DB::raw('SUM(price) as price'))
                ->where('product_id',$productPurchaseDetail->product_id)
                ->where('product_category_id',$productPurchaseDetail->product_category_id)
                ->where('product_sub_category_id',$productPurchaseDetail->product_sub_category_id)
                ->where('product_brand_id',$productPurchaseDetail->product_brand_id)
                ->where('product_sale_return_details.created_at','>=',date('Y-m-d').' 00:00:00')
                ->where('product_sale_return_details.created_at','<=',date('Y-m-d').' 23:59:59')
                ->groupBy('product_id')
                ->groupBy('product_category_id')
                ->groupBy('product_sub_category_id')
                ->groupBy('product_brand_id')
                ->first();


            if(!empty($productSaleReturnDetails))
            {
                $sale_return_total_qty = $productSaleReturnDetails->qty;
                $sale_return_total_amount = $productSaleReturnDetails->price;
                $sale_return_average_price = $sale_return_total_amount/$productSaleReturnDetails->qty;

                if($sale_return_total_qty > 0){
                    //$loss_or_profit = $sale_return_average_price - ($purchase_average_price*$sale_return_total_qty);
                    $loss_or_profit = ($sale_return_total_qty*$sale_return_total_amount) - ($purchase_average_price*$sale_return_total_qty);
                    $sum_loss_or_profit -= $loss_or_profit;
                }
            }

        }

        $productSaleDiscount = DB::table('product_sales')
            ->select( DB::raw('SUM(discount_amount) as total_discount'))
            ->where('store_id',$store_id)
            ->where('created_at','>=',date('Y-m-d').' 00:00:00')
            ->where('created_at','<=',date('Y-m-d').' 23:59:59')
            ->first();

        $sum_total_discount = 0;

        if($productSaleDiscount){
            $sum_total_discount = $productSaleDiscount->total_discount;
        }

        return $sum_loss_or_profit - $sum_total_discount;
    }

    function sum_stock_purchase_price($store_id){
//        $transactions = \App\Transaction::where('store_id',$store_id)->where('transaction_type','purchase')->get();
//        $sum_amount = 0;
//        foreach($transactions as $key => $transaction){
//            $sum_amount += $transaction->amount;
//        }
//
//        $fromStockTransferDetails = DB::table('stock_transfer_details')
//            ->join('stock_transfers','stock_transfers.id','=','stock_transfer_details.stock_transfer_id')
//            ->select(DB::raw('SUM(stock_transfer_details.sub_total) as from_stock_transfer_sum_sub_total'))
//            ->where('stock_transfers.from_store_id',$store_id)
//            ->first();
//
//        if($fromStockTransferDetails){
//            $from_stock_transfer_sum_sub_total = $fromStockTransferDetails->from_stock_transfer_sum_sub_total;
//            $sum_amount -= $from_stock_transfer_sum_sub_total;
//        }
//
//        $toStockTransferDetails = DB::table('stock_transfer_details')
//            ->join('stock_transfers','stock_transfers.id','=','stock_transfer_details.stock_transfer_id')
//            ->select(DB::raw('SUM(stock_transfer_details.sub_total) as to_stock_transfer_sum_sub_total'))
//            ->where('stock_transfers.to_store_id',$store_id)
//            ->first();
//
//        if($toStockTransferDetails){
//            $to_stock_transfer_sum_sub_total = $toStockTransferDetails->to_stock_transfer_sum_sub_total;
//            $sum_amount += $to_stock_transfer_sum_sub_total;
//        }

        $sum_total_purchase_amount = 0;
        $transaction_total_purchases = \App\Transaction::where('store_id',$store_id)
            ->select(DB::raw('SUM(amount) as total_purchase'))
            ->where('transaction_type','purchase')
            ->first();

        if(!empty($transaction_total_purchases)){
            $sum_total_purchase_amount += $transaction_total_purchases->total_purchase;
        }



        $fromStockTransferDetails = DB::table('stock_transfer_details')
            ->join('stock_transfers','stock_transfers.id','=','stock_transfer_details.stock_transfer_id')
            ->select(DB::raw('SUM(stock_transfer_details.sub_total) as from_stock_transfer_sum_sub_total'))
            ->where('stock_transfers.from_store_id',$store_id)
            ->first();

        if($fromStockTransferDetails){
            $from_stock_transfer_sum_sub_total = $fromStockTransferDetails->from_stock_transfer_sum_sub_total;
            $sum_total_purchase_amount -= $from_stock_transfer_sum_sub_total;
        }



        $toStockTransferDetails = DB::table('stock_transfer_details')
            ->join('stock_transfers','stock_transfers.id','=','stock_transfer_details.stock_transfer_id')
            ->select(DB::raw('SUM(stock_transfer_details.sub_total) as to_stock_transfer_sum_sub_total'))
            ->where('stock_transfers.to_store_id',$store_id)
            ->first();

        if($toStockTransferDetails){
            $to_stock_transfer_sum_sub_total = $toStockTransferDetails->to_stock_transfer_sum_sub_total;
            $sum_total_purchase_amount += $to_stock_transfer_sum_sub_total;
        }

        $sum_stock_purchase_price = $sum_total_purchase_amount;

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

function sum_today_sale_return_price($store_id){
    // sale return today

    $sum_today_sale_return_price = 0;
//    $productTodaySaleReturnDetails = DB::table('product_sale_return_details')
//        ->leftJoin('product_sale_returns','product_sale_return_details.product_sale_return_id','=','product_sale_returns.id')
//        ->select(
//            'product_sale_return_details.product_id',
//            DB::raw('SUM(product_sale_return_details.qty) as qty'),
//            DB::raw('SUM(product_sale_return_details.price) as price')
//        )
//        ->where('product_sale_returns.store_id',$store_id)
//        ->where('product_sale_return_details.created_at','>=',date('Y-m-d').' 00:00:00')
//        ->where('product_sale_return_details.created_at','<=',date('Y-m-d').' 23:59:59')
//        ->groupBy('product_sale_return_details.product_id')
//        ->get();
//
//    if(!empty($productTodaySaleReturnDetails)) {
//        foreach ($productTodaySaleReturnDetails as $productTodaySaleReturnDetail){
//            //$product_id = $productTodaySaleReturnDetail->product_id;
//            $sale_return_total_qty = $productTodaySaleReturnDetail->qty;
//            $sale_return_total_price = $productTodaySaleReturnDetail->price;
//            $sum_today_sale_return_price += $sale_return_total_qty*$sale_return_total_price;
//        }
//
//    }

    $transaction_total_today_sale_returns = \App\Transaction::where('store_id',$store_id)
        ->select(DB::raw('SUM(amount) as total_sale_return'))
        ->where('transaction_type','sale return')
        ->where('created_at','>=',date('Y-m-d').' 00:00:00')
        ->where('created_at','<=',date('Y-m-d').' 23:59:59')
        ->first();

    if(!empty($transaction_total_today_sale_returns)){
        $sum_today_sale_return_price += $transaction_total_today_sale_returns->total_sale_return;
    }

    return $sum_today_sale_return_price;
}

function sum_last_thirty_day_sale_return_price($store_id){
    // sale return last 30 day

    $sum_last_thirty_day_sale_return_price = 0;
//    $productTodaySaleReturnDetails = DB::table('product_sale_return_details')
//        ->leftJoin('product_sale_returns','product_sale_return_details.product_sale_return_id','=','product_sale_returns.id')
//        ->select(
//            'product_sale_return_details.product_id',
//            DB::raw('SUM(product_sale_return_details.qty) as qty'),
//            DB::raw('SUM(product_sale_return_details.price) as price')
//        )
//        ->where('product_sale_returns.store_id',$store_id)
//        ->where('product_sale_return_details.created_at','>=',date('Y-m-d',strtotime('-30 days')).' 00:00:00')
//        ->where('product_sale_return_details.created_at','<=',date('Y-m-d').' 23:59:59')
//        ->groupBy('product_sale_return_details.product_id')
//        ->get();
//
//    if(!empty($productTodaySaleReturnDetails)) {
//        foreach ($productTodaySaleReturnDetails as $productTodaySaleReturnDetail){
//            //$product_id = $productTodaySaleReturnDetail->product_id;
//            $sale_return_total_qty = $productTodaySaleReturnDetail->qty;
//            $sale_return_total_price = $productTodaySaleReturnDetail->price;
//            $sum_last_thirty_day_sale_return_price += $sale_return_total_qty*$sale_return_total_price;
//        }
//
//    }

    $transaction_total_last_thirty_day_sale_returns = \App\Transaction::where('store_id',$store_id)
        ->select(DB::raw('SUM(amount) as total_sale_return'))
        ->where('transaction_type','sale return')
        ->where('created_at','>=',date('Y-m-d',strtotime('-30 days')).' 00:00:00')
        ->where('created_at','<=',date('Y-m-d').' 23:59:59')
        ->first();

    if(!empty($transaction_total_last_thirty_day_sale_returns)){
        $sum_last_thirty_day_sale_return_price += $transaction_total_last_thirty_day_sale_returns->total_sale_return;
    }

    return $sum_last_thirty_day_sale_return_price;
}

function sum_sale_return_price($store_id){
    // sale return

    $sum_sale_return_price = 0;
//    $productTodaySaleReturnDetails = DB::table('product_sale_return_details')
//        ->leftJoin('product_sale_returns','product_sale_return_details.product_sale_return_id','=','product_sale_returns.id')
//        ->select(
//            'product_sale_return_details.product_id',
//            DB::raw('SUM(product_sale_return_details.qty) as qty'),
//            DB::raw('SUM(product_sale_return_details.price) as price')
//        )
//        ->where('product_sale_returns.store_id',$store_id)
//        ->groupBy('product_sale_return_details.product_id')
//        ->get();
//
//    if(!empty($productTodaySaleReturnDetails)) {
//        foreach ($productTodaySaleReturnDetails as $productTodaySaleReturnDetail){
//            //$product_id = $productTodaySaleReturnDetail->product_id;
//            $sale_return_total_qty = $productTodaySaleReturnDetail->qty;
//            $sale_return_total_price = $productTodaySaleReturnDetail->price;
//            $sum_sale_return_price += $sale_return_total_qty*$sale_return_total_price;
//        }
//
//    }

    $transaction_total_sale_returns = \App\Transaction::where('store_id',$store_id)
        ->select(DB::raw('SUM(amount) as total_sale_return'))
        ->where('transaction_type','sale return')
        ->first();

    if(!empty($transaction_total_sale_returns)){
        $sum_sale_return_price += $transaction_total_sale_returns->total_sale_return;
    }

    return $sum_sale_return_price;
}

function sum_last_thirty_day_sale_price($store_id){

    $sum_last_thirty_day_sale_price = 0;
    // sale last 30 day
//    $productLastThirtyDaySaleDetail = DB::table('product_sale_details')
//        ->join('product_sales','product_sale_details.product_sale_id','=','product_sales.id')
//        ->select(
//            DB::raw('SUM(product_sales.total_amount) as total_amount')
//        )
//        ->where('product_sales.store_id',$store_id)
//        ->where('product_sale_details.created_at','>=',date('Y-m-d',strtotime('-30 days')).' 00:00:00')
//        ->where('product_sale_details.created_at','<=',date('Y-m-d').' 23:59:59')
//        ->first();
//
//    if(!empty($productLastThirtyDaySaleDetail)){
//        $sum_last_thirty_day_sale_price = $productLastThirtyDaySaleDetail->total_amount;
//    }

    $transaction_last_thirty_day_sales = \App\Transaction::where('store_id',$store_id)
        ->select(DB::raw('SUM(amount) as total_sale'))
        ->where('transaction_type','sale')
        ->where('created_at','>=',date('Y-m-d',strtotime('-30 days')).' 00:00:00')
        ->where('created_at','<=',date('Y-m-d').' 23:59:59')
        ->first();

    if(!empty($transaction_last_thirty_day_sales)){
        $sum_last_thirty_day_sale_price += $transaction_last_thirty_day_sales->total_sale;
    }


    return $sum_last_thirty_day_sale_price;
}

function sum_today_sale_price($store_id){

    $sum_today_sale_price = 0;
    // sale last 30 day
//    $productTodaySaleDetail = DB::table('product_sale_details')
//        ->join('product_sales','product_sale_details.product_sale_id','=','product_sales.id')
//        ->select(
//            DB::raw('SUM(product_sales.total_amount) as total_amount')
//        )
//        ->where('product_sales.store_id',$store_id)
//        ->where('product_sale_details.created_at','>=',date('Y-m-d').' 00:00:00')
//        ->where('product_sale_details.created_at','<=',date('Y-m-d').' 23:59:59')
//        ->first();
//
//    if(!empty($productTodaySaleDetail)){
//        $sum_today_sale_price = $productTodaySaleDetail->total_amount;
//    }

    $transaction_today_sales = \App\Transaction::where('store_id',$store_id)
        ->select(DB::raw('SUM(amount) as total_sale'))
        ->where('transaction_type','sale')
        ->where('created_at','>=',date('Y-m-d').' 00:00:00')
        ->where('created_at','<=',date('Y-m-d').' 23:59:59')
        ->first();

    if(!empty($transaction_today_sales)){
        $sum_today_sale_price += $transaction_today_sales->total_sale;
    }


    return $sum_today_sale_price;
}

function sum_sale_price($store_id){

    $sum_sale_price = 0;

//    $productTodaySaleDetail = DB::table('product_sale_details')
//        ->join('product_sales','product_sale_details.product_sale_id','=','product_sales.id')
//        ->select(
//            DB::raw('SUM(product_sales.total_amount) as total_amount')
//        )
//        ->where('product_sales.store_id',$store_id)
//        ->first();
//
//    if(!empty($productTodaySaleDetail)){
//        $sum_sale_price = $productTodaySaleDetail->total_amount;
//    }

    $transaction_total_sales = \App\Transaction::where('store_id',$store_id)
        ->select(DB::raw('SUM(amount) as total_sale'))
        ->where('transaction_type','sale')
        ->first();

    if(!empty($transaction_total_sales)){
        $sum_sale_price += $transaction_total_sales->total_sale;
    }

    return $sum_sale_price;
}

function sum_today_purchase_amount($store_id){

    $sum_today_purchase_amount = 0;

    $transaction_total_purchases = \App\Transaction::where('store_id',$store_id)
        ->select(DB::raw('SUM(amount) as total_purchase'))
        ->where('transaction_type','purchase')
        ->where('created_at','>=',date('Y-m-d').' 00:00:00')
        ->where('created_at','<=',date('Y-m-d').' 23:59:59')
        ->first();

    if(!empty($transaction_total_purchases)){
        $sum_today_purchase_amount += $transaction_total_purchases->total_purchase;
    }

    $fromStockTransferDetails = DB::table('stock_transfer_details')
        ->leftJoin('stock_transfers','stock_transfers.id','=','stock_transfer_details.stock_transfer_id')
        ->select(DB::raw('SUM(stock_transfer_details.sub_total) as from_stock_transfer_sum_sub_total'))
        ->where('stock_transfers.from_store_id',$store_id)
        ->where('stock_transfer_details.created_at','>=',date('Y-m-d').' 00:00:00')
        ->where('stock_transfer_details.created_at','<=',date('Y-m-d').' 23:59:59')
        ->first();

    if($fromStockTransferDetails){
        $from_stock_transfer_sum_sub_total = $fromStockTransferDetails->from_stock_transfer_sum_sub_total;
        $sum_today_purchase_amount -= $from_stock_transfer_sum_sub_total;
    }



    $toStockTransferDetails = DB::table('stock_transfer_details')
        ->leftJoin('stock_transfers','stock_transfers.id','=','stock_transfer_details.stock_transfer_id')
        ->select(DB::raw('SUM(stock_transfer_details.sub_total) as to_stock_transfer_sum_sub_total'))
        ->where('stock_transfers.to_store_id',$store_id)
        ->where('stock_transfer_details.created_at','>=',date('Y-m-d').' 00:00:00')
        ->where('stock_transfer_details.created_at','<=',date('Y-m-d').' 23:59:59')
        ->first();

    if($toStockTransferDetails){
        $to_stock_transfer_sum_sub_total = $toStockTransferDetails->to_stock_transfer_sum_sub_total;
        $sum_today_purchase_amount += $to_stock_transfer_sum_sub_total;
    }

    return $sum_today_purchase_amount;
}

function sum_last_thirty_day_purchase_amount($store_id){

    $sum_last_thirty_day_purchase_amount = 0;

    $transaction_total_purchases = \App\Transaction::where('store_id',$store_id)
        ->select(DB::raw('SUM(amount) as total_purchase'))
        ->where('transaction_type','purchase')
        ->where('created_at','>=',date('Y-m-d',strtotime('-30 days')).' 00:00:00')
        ->where('created_at','<=',date('Y-m-d').' 23:59:59')
        ->first();

    if(!empty($transaction_total_purchases)){
        $sum_last_thirty_day_purchase_amount += $transaction_total_purchases->total_purchase;
    }

    $fromStockTransferDetails = DB::table('stock_transfer_details')
        ->leftJoin('stock_transfers','stock_transfers.id','=','stock_transfer_details.stock_transfer_id')
        ->select(DB::raw('SUM(stock_transfer_details.sub_total) as from_stock_transfer_sum_sub_total'))
        ->where('stock_transfers.from_store_id',$store_id)
        ->where('stock_transfer_details.created_at','>=',date('Y-m-d',strtotime('-30 days')).' 00:00:00')
        ->where('stock_transfer_details.created_at','<=',date('Y-m-d').' 23:59:59')
        ->first();

    if($fromStockTransferDetails){
        $from_stock_transfer_sum_sub_total = $fromStockTransferDetails->from_stock_transfer_sum_sub_total;
        $sum_last_thirty_day_purchase_amount -= $from_stock_transfer_sum_sub_total;
    }



    $toStockTransferDetails = DB::table('stock_transfer_details')
        ->leftJoin('stock_transfers','stock_transfers.id','=','stock_transfer_details.stock_transfer_id')
        ->select(DB::raw('SUM(stock_transfer_details.sub_total) as to_stock_transfer_sum_sub_total'))
        ->where('stock_transfers.to_store_id',$store_id)
        ->where('stock_transfer_details.created_at','>=',date('Y-m-d',strtotime('-30 days')).' 00:00:00')
        ->where('stock_transfer_details.created_at','<=',date('Y-m-d').' 23:59:59')
        ->first();

    if($toStockTransferDetails){
        $to_stock_transfer_sum_sub_total = $toStockTransferDetails->to_stock_transfer_sum_sub_total;
        $sum_last_thirty_day_purchase_amount += $to_stock_transfer_sum_sub_total;
    }

    return $sum_last_thirty_day_purchase_amount;
}

function sum_total_purchase_amount($store_id){

    $sum_total_purchase_amount = 0;

    $transaction_total_purchases = \App\Transaction::where('store_id',$store_id)
        ->select(DB::raw('SUM(amount) as total_purchase'))
        ->where('transaction_type','purchase')
        ->first();

    if(!empty($transaction_total_purchases)){
        $sum_total_purchase_amount += $transaction_total_purchases->total_purchase;
    }



    $fromStockTransferDetails = DB::table('stock_transfer_details')
        ->join('stock_transfers','stock_transfers.id','=','stock_transfer_details.stock_transfer_id')
        ->select(DB::raw('SUM(stock_transfer_details.sub_total) as from_stock_transfer_sum_sub_total'))
        ->where('stock_transfers.from_store_id',$store_id)
        ->first();

    if($fromStockTransferDetails){
        $from_stock_transfer_sum_sub_total = $fromStockTransferDetails->from_stock_transfer_sum_sub_total;
        $sum_total_purchase_amount -= $from_stock_transfer_sum_sub_total;
    }



    $toStockTransferDetails = DB::table('stock_transfer_details')
        ->join('stock_transfers','stock_transfers.id','=','stock_transfer_details.stock_transfer_id')
        ->select(DB::raw('SUM(stock_transfer_details.sub_total) as to_stock_transfer_sum_sub_total'))
        ->where('stock_transfers.to_store_id',$store_id)
        ->first();

    if($toStockTransferDetails){
        $to_stock_transfer_sum_sub_total = $toStockTransferDetails->to_stock_transfer_sum_sub_total;
        $sum_total_purchase_amount += $to_stock_transfer_sum_sub_total;
    }

    return $sum_total_purchase_amount;
}

?>

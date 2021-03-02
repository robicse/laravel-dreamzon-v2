@extends('backend._partial.dashboard')

@section('content')
    <main class="app-content">
        <div class="app-title">
            <div>
                <h1><i class="fa fa-dashboard"></i> Dashboard</h1>
            </div>
            <ul class="app-breadcrumb breadcrumb">
                <li class="breadcrumb-item"><i class="fa fa-home fa-lg"></i></li>
                <li class="breadcrumb-item"><a href="">Dashboard</a></li>
            </ul>

        </div>
        <div class="row">
            @if(Auth::User()->getRoleNames()[0] == "Admin")
                @if(!empty($stores))
                    @foreach($stores as $store)
                        <div class="col-md-12">
                            <h1 class="text-center">{{$store->name}}</h1>
                        </div>

                        @php
                            // total purchase
                            $sum_amount = 0;
                            $sum_last_thirty_day_amount = 0;
                            $sum_today_amount = 0;




                            $sum_purchase_price = 0;
                            $sum_sale_price = 0;
                            $sum_sale_return_price = 0;
                            $sum_loss_or_profit = 0;
                            $sum_store_transfer_out = 0;
                            $from_stock_transfer_sum_sub_total = 0;
                            $to_stock_transfer_sum_sub_total = 0;

                            // today
                            $sum_today_purchase_price = 0;
                            $sum_today_sale_price = 0;
                            $sum_today_sale_return_price = 0;
                            $sum_today_loss_or_profit = 0;

                            // last 30 day
                            $sum_last_thirty_day_purchase_price = 0;
                            $sum_last_thirty_day_sale_price = 0;
                            $sum_last_thirty_day_sale_return_price = 0;
                            $sum_last_thirty_day_loss_or_profit = 0;






                            $transactions = \App\Transaction::where('store_id',$store->id)->where('transaction_type','purchase')->get();

                            foreach($transactions as $key => $transaction){
                                $sum_amount += $transaction->amount;
                            }

























                            // last 30 day purchase
                            $sum_last_thirty_day_amount = 0;
                            $transaction_last_thirty_days = \App\Transaction::where('store_id',$store->id)
                                        ->where('created_at','>=',date('Y-m-d',strtotime('-30 days')).' 00:00:00')
                                        ->where('created_at','<=',date('Y-m-d').' 23:59:59')
                                        ->where('transaction_type','purchase')
                                        ->get();

                            foreach($transaction_last_thirty_days as $key => $transaction_last_thirty_day){
                                $sum_last_thirty_day_amount += $transaction_last_thirty_day->amount;
                            }

                            // Today purchase
                            $sum_today_amount = 0;
                            $transaction_todays = \App\Transaction::where('store_id',$store->id)
                                        ->where('created_at','>=',date('Y-m-d').' 00:00:00')
                                        ->where('created_at','<=',date('Y-m-d').' 23:59:59')
                                        ->where('transaction_type','purchase')
                                        ->get();

                            foreach($transaction_todays as $key => $transaction_today){
                                $sum_today_amount += $transaction_today->amount;
                            }



                            $productPurchaseDetails = DB::table('product_purchase_details')
                                ->join('product_purchases','product_purchases.id','=','product_purchase_details.product_purchase_id')
                                ->select('product_id','product_category_id','product_sub_category_id','product_brand_id', DB::raw('SUM(qty) as qty'), DB::raw('SUM(price) as price'), DB::raw('SUM(sub_total) as sub_total'))
                                ->where('product_purchases.store_id',$store->id)
                                ->groupBy('product_id')
                                ->groupBy('product_category_id')
                                ->groupBy('product_sub_category_id')
                                ->groupBy('product_brand_id')
                                ->get();





                            $sum_stock_purchase_price = $sum_amount;

                            if(!empty($productPurchaseDetails)){
                                foreach($productPurchaseDetails as $key => $productPurchaseDetail){
                                    $purchase_average_price = $productPurchaseDetail->sub_total/$productPurchaseDetail->qty;
                                    $sum_purchase_price += $productPurchaseDetail->sub_total;

                                    // sale total
                                    $productSaleDetails = DB::table('product_sale_details')
                                        ->join('product_sales','product_sale_details.product_sale_id','=','product_sales.id')
                                        ->select('product_sale_details.product_id','product_sale_details.product_category_id','product_sale_details.product_sub_category_id','product_sale_details.product_brand_id', DB::raw('SUM(qty) as qty'), DB::raw('SUM(price) as price'), DB::raw('SUM(sub_total) as sub_total'))
                                        ->where('product_sale_details.product_id',$productPurchaseDetail->product_id)
                                        ->where('product_sale_details.product_category_id',$productPurchaseDetail->product_category_id)
                                        ->where('product_sale_details.product_sub_category_id',$productPurchaseDetail->product_sub_category_id)
                                        ->where('product_sale_details.product_brand_id',$productPurchaseDetail->product_brand_id)
                                        ->where('product_sales.store_id',$store->id)
                                        ->groupBy('product_sale_details.product_id')
                                        ->groupBy('product_sale_details.product_category_id')
                                        ->groupBy('product_sale_details.product_sub_category_id')
                                        ->groupBy('product_sale_details.product_brand_id')
                                        ->first();

                                    if(!empty($productSaleDetails))
                                    {
                                        $sale_total_qty = $productSaleDetails->qty;
                                        $sum_sale_price += $productSaleDetails->sub_total;
                                        $sale_average_price = $productSaleDetails->sub_total/$productSaleDetails->qty;

                                        if($sale_total_qty > 0){
                                            $loss_or_profit = ($sale_average_price*$sale_total_qty) - ($purchase_average_price*$sale_total_qty);
                                            $sum_loss_or_profit += $loss_or_profit;


                                            $sale_total_qty = $productSaleDetails->qty;
                                            $sum_stock_purchase_price -= $purchase_average_price*$sale_total_qty;
                                        }
                                    }

                                    // sale today
                                    $productTodaySaleDetails = DB::table('product_sale_details')
                                        ->join('product_sales','product_sale_details.product_sale_id','=','product_sales.id')
                                        ->select('product_sale_details.product_id','product_sale_details.product_category_id','product_sale_details.product_sub_category_id','product_sale_details.product_brand_id', DB::raw('SUM(qty) as qty'), DB::raw('SUM(price) as price'), DB::raw('SUM(sub_total) as sub_total'))
                                        ->where('product_sale_details.product_id',$productPurchaseDetail->product_id)
                                        ->where('product_sale_details.product_category_id',$productPurchaseDetail->product_category_id)
                                        ->where('product_sale_details.product_sub_category_id',$productPurchaseDetail->product_sub_category_id)
                                        ->where('product_sale_details.product_brand_id',$productPurchaseDetail->product_brand_id)
                                        ->where('product_sales.store_id',$store->id)
                                        ->where('product_sale_details.created_at','>=',date('Y-m-d').' 00:00:00')
                                        ->where('product_sale_details.created_at','<=',date('Y-m-d').' 23:59:59')
                                        ->groupBy('product_sale_details.product_id')
                                        ->groupBy('product_sale_details.product_category_id')
                                        ->groupBy('product_sale_details.product_sub_category_id')
                                        ->groupBy('product_sale_details.product_brand_id')
                                        ->first();

                                    if(!empty($productTodaySaleDetails))
                                    {
                                        $today_sale_total_qty = $productTodaySaleDetails->qty;
                                        $sum_today_sale_price += $productTodaySaleDetails->sub_total;
                                        $today_sale_average_price = $productTodaySaleDetails->sub_total/$productTodaySaleDetails->qty;

                                        if($today_sale_total_qty > 0){
                                            $today_loss_or_profit = ($today_sale_average_price*$today_sale_total_qty) - ($purchase_average_price*$today_sale_total_qty);
                                            $sum_today_loss_or_profit += $today_loss_or_profit;
                                        }
                                    }

                                    // sale last 30 day
                                    $productLastThirtyDaySaleDetails = DB::table('product_sale_details')
                                        ->join('product_sales','product_sale_details.product_sale_id','=','product_sales.id')
                                        ->select('product_sale_details.product_id','product_sale_details.product_category_id','product_sale_details.product_sub_category_id','product_sale_details.product_brand_id', DB::raw('SUM(qty) as qty'), DB::raw('SUM(price) as price'), DB::raw('SUM(sub_total) as sub_total'))
                                        ->where('product_sale_details.product_id',$productPurchaseDetail->product_id)
                                        ->where('product_sale_details.product_category_id',$productPurchaseDetail->product_category_id)
                                        ->where('product_sale_details.product_sub_category_id',$productPurchaseDetail->product_sub_category_id)
                                        ->where('product_sale_details.product_brand_id',$productPurchaseDetail->product_brand_id)
                                        ->where('product_sales.store_id',$store->id)
                                        ->where('product_sale_details.created_at','>=',date('Y-m-d',strtotime('-30 days')).' 00:00:00')
                                        ->where('product_sale_details.created_at','<=',date('Y-m-d').' 23:59:59')
                                        ->groupBy('product_sale_details.product_id')
                                        ->groupBy('product_sale_details.product_category_id')
                                        ->groupBy('product_sale_details.product_sub_category_id')
                                        ->groupBy('product_sale_details.product_brand_id')
                                        ->first();

                                    if(!empty($productLastThirtyDaySaleDetails))
                                    {
                                        $last_thirty_day_sale_total_qty = $productLastThirtyDaySaleDetails->qty;
                                        $sum_last_thirty_day_sale_price += $productLastThirtyDaySaleDetails->sub_total;
                                        $last_thirty_day_sale_average_price = $productLastThirtyDaySaleDetails->sub_total/$productLastThirtyDaySaleDetails->qty;

                                        if($last_thirty_day_sale_total_qty > 0){
                                            $last_thirty_day_loss_or_profit = ($last_thirty_day_sale_average_price*$last_thirty_day_sale_total_qty) - ($purchase_average_price*$last_thirty_day_sale_total_qty);
                                            $sum_last_thirty_day_loss_or_profit += $last_thirty_day_loss_or_profit;
                                        }
                                    }

                                    // sale return
                                    $productReturnDetails = DB::table('product_sale_return_details')
                                        ->select('product_id','product_category_id','product_sub_category_id','product_brand_id', DB::raw('SUM(qty) as qty'), DB::raw('SUM(price) as price'))
                                        ->where('product_id',$productPurchaseDetail->product_id)
                                        ->where('product_category_id',$productPurchaseDetail->product_category_id)
                                        ->where('product_sub_category_id',$productPurchaseDetail->product_sub_category_id)
                                        ->where('product_brand_id',$productPurchaseDetail->product_brand_id)
                                        ->groupBy('product_id')
                                        ->groupBy('product_category_id')
                                        ->groupBy('product_sub_category_id')
                                        ->groupBy('product_brand_id')
                                        ->first();

                                    if(!empty($productReturnDetails))
                                    {
                                        $sale_return_total_qty = $productReturnDetails->qty;
                                        $sale_return_total_amount = $productReturnDetails->price;
                                        $sum_sale_return_price += $productReturnDetails->price;
                                        $sale_return_average_price = $sale_return_total_amount/$productReturnDetails->qty;

                                        if($sale_return_total_qty > 0){
                                            $loss_or_profit = $sale_return_average_price - ($purchase_average_price*$sale_return_total_qty);
                                            $sum_loss_or_profit -= $loss_or_profit;
                                        }
                                    }

                                    // sale return last 30 day
                                    $productLastThirtyDaySaleReturnDetails = DB::table('product_sale_return_details')
                                        ->select('product_id','product_category_id','product_sub_category_id','product_brand_id', DB::raw('SUM(qty) as qty'), DB::raw('SUM(price) as price'))
                                        ->where('product_id',$productPurchaseDetail->product_id)
                                        ->where('product_category_id',$productPurchaseDetail->product_category_id)
                                        ->where('product_sub_category_id',$productPurchaseDetail->product_sub_category_id)
                                        ->where('product_brand_id',$productPurchaseDetail->product_brand_id)
                                        ->where('product_sale_return_details.created_at','>=',date('Y-m-d',strtotime('-30 days')).' 00:00:00')
                                        ->where('product_sale_return_details.created_at','<=',date('Y-m-d').' 23:59:59')
                                        ->groupBy('product_id')
                                        ->groupBy('product_category_id')
                                        ->groupBy('product_sub_category_id')
                                        ->groupBy('product_brand_id')
                                        ->first();

                                    if(!empty($productLastThirtyDaySaleReturnDetails))
                                    {
                                        $sale_return_last_thirty_day_total_qty = $productLastThirtyDaySaleReturnDetails->qty;
                                        $sale_return_last_thirty_day_total_amount = $productLastThirtyDaySaleReturnDetails->price;
                                        $sum_last_thirty_day_sale_return_price += $productLastThirtyDaySaleReturnDetails->price;
                                        $last_thirty_day_sale_return_average_price = $sale_return_last_thirty_day_total_amount/$productLastThirtyDaySaleReturnDetails->qty;

                                        if($sale_return_last_thirty_day_total_qty > 0){
                                            $last_thirty_day_loss_or_profit = $last_thirty_day_sale_return_average_price - ($purchase_average_price*$sale_return_last_thirty_day_total_qty);
                                            $sum_last_thirty_day_loss_or_profit -= $last_thirty_day_loss_or_profit;
                                        }
                                    }

                                    // sale return today
                                    $productTodaySaleReturnDetails = DB::table('product_sale_return_details')
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

                                    if(!empty($productTodaySaleReturnDetails))
                                    {
                                        $sale_return_today_total_qty = $productTodaySaleReturnDetails->qty;
                                        $sale_return_today_total_amount = $productTodaySaleReturnDetails->price;
                                        $sum_today_sale_return_price += $productTodaySaleReturnDetails->price;
                                        $today_sale_return_average_price = $sale_return_today_total_amount/$productTodaySaleReturnDetails->qty;

                                        if($sale_return_today_total_qty > 0){
                                            $today_loss_or_profit = $today_sale_return_average_price - ($purchase_average_price*$sale_return_today_total_qty);
                                            $sum_today_loss_or_profit -= $today_loss_or_profit;
                                        }
                                    }
                                }
                            }



                            // today purchase
                            $productTodayPurchaseDetails = DB::table('product_purchase_details')
                                ->join('product_purchases','product_purchases.id','=','product_purchase_details.product_purchase_id')
                                ->select('product_id','product_category_id','product_sub_category_id','product_brand_id', DB::raw('SUM(qty) as qty'), DB::raw('SUM(price) as price'), DB::raw('SUM(sub_total) as sub_total'))
                                ->where('product_purchases.store_id',$store->id)
                                ->where('product_purchase_details.created_at','>=',date('Y-m-d').' 00:00:00')
                                ->where('product_purchase_details.created_at','<=',date('Y-m-d').' 23:59:59')
                                ->groupBy('product_id')
                                ->groupBy('product_category_id')
                                ->groupBy('product_sub_category_id')
                                ->groupBy('product_brand_id')
                                ->get();

                            if(!empty($productTodayPurchaseDetails)){
                                foreach($productTodayPurchaseDetails as $key => $productTodayPurchaseDetail){
                                    $purchase_today_average_price = $productTodayPurchaseDetail->sub_total/$productTodayPurchaseDetail->qty;
                                    $sum_today_purchase_price += $productTodayPurchaseDetail->sub_total;
                                }
                            }

                            // last 30 day purchase
                            //$last_thirty_day = date('Y-m-d', strtotime('-30 days'));
                            $productLastThirtyDayPurchaseDetails = DB::table('product_purchase_details')
                                ->join('product_purchases','product_purchases.id','=','product_purchase_details.product_purchase_id')
                                ->select('product_id','product_category_id','product_sub_category_id','product_brand_id', DB::raw('SUM(qty) as qty'), DB::raw('SUM(price) as price'), DB::raw('SUM(sub_total) as sub_total'))
                                ->where('product_purchases.store_id',$store->id)
                                ->where('product_purchase_details.created_at','>=',date('Y-m-d', strtotime('-30 days')).' 00:00:00')
                                ->where('product_purchase_details.created_at','<=',date('Y-m-d').' 23:59:59')
                                ->groupBy('product_id')
                                ->groupBy('product_category_id')
                                ->groupBy('product_sub_category_id')
                                ->groupBy('product_brand_id')
                                ->get();

                            if(!empty($productLastThirtyDayPurchaseDetails)){
                                foreach($productLastThirtyDayPurchaseDetails as $key => $productLastThirtyDayPurchaseDetail){
                                    $purchase_last_thirty_day_average_price = $productLastThirtyDayPurchaseDetail->sub_total/$productLastThirtyDayPurchaseDetail->qty;
                                    $sum_last_thirty_day_purchase_price += $productLastThirtyDayPurchaseDetail->sub_total;
                                }
                            }


                            $fromStockTransferDetails = DB::table('stock_transfer_details')
                                ->join('stock_transfers','stock_transfers.id','=','stock_transfer_details.stock_transfer_id')
                                ->select(DB::raw('SUM(stock_transfer_details.sub_total) as from_stock_transfer_sum_sub_total'))
                                ->where('stock_transfers.from_store_id',$store->id)
                                ->first();

                            if($fromStockTransferDetails){
                                 $from_stock_transfer_sum_sub_total = $fromStockTransferDetails->from_stock_transfer_sum_sub_total;
                                 $sum_amount -= $from_stock_transfer_sum_sub_total;
                            }

                            $toStockTransferDetails = DB::table('stock_transfer_details')
                                ->join('stock_transfers','stock_transfers.id','=','stock_transfer_details.stock_transfer_id')
                                ->select(DB::raw('SUM(stock_transfer_details.sub_total) as to_stock_transfer_sum_sub_total'))
                                ->where('stock_transfers.to_store_id',$store->id)
                                ->first();

                            if($toStockTransferDetails){
                                 $to_stock_transfer_sum_sub_total = $toStockTransferDetails->to_stock_transfer_sum_sub_total;
                                 $sum_amount += $to_stock_transfer_sum_sub_total;
                            }
                        @endphp

                        <div class="col-md-4 ">
                            <div class="widget-small primary coloured-icon"><i class="icon fa fa-users fa-3x"></i>
                                <div class="info">
                                    <h4>Total Purchase</h4>
                                    <p><b>{{number_format($sum_amount, 2, '.', ',')}}</b></p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4 ">
                            <div class="widget-small primary coloured-icon"><i class="icon fa fa-users fa-3x"></i>
                                <div class="info">
                                    <h4>Last 30 Day Purchase</h4>
                                    <p><b>{{number_format($sum_last_thirty_day_amount, 2, '.', ',')}}</b></p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4 ">
                            <div class="widget-small primary coloured-icon"><i class="icon fa fa-users fa-3x"></i>
                                <div class="info">
                                    <h4>Today Purchase</h4>
                                    <p><b>{{number_format($sum_today_amount, 2, '.', ',')}}</b></p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="widget-small danger coloured-icon"><i class="icon fas fa-money-check-alt "></i>
                                <div class="info">
                                    <h4>Total Sell</h4>
                                    <p><b>{{number_format($sum_sale_price, 2, '.', ',')}}</b></p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="widget-small danger coloured-icon"><i class="icon fas fa-money-check-alt "></i>
                                <div class="info">
                                    <h4>Last 30 Day Sell</h4>
                                    <p><b>{{number_format($sum_last_thirty_day_sale_price, 2, '.', ',')}}</b></p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="widget-small danger coloured-icon"><i class="icon fas fa-money-check-alt "></i>
                                <div class="info">
                                    <h4>Today Sell</h4>
                                    <p><b>{{number_format($sum_today_sale_price, 2, '.', ',')}}</b></p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="widget-small warning coloured-icon"><i class="icon fa fa-files-o fa-3x"></i>
                                <div class="info">
                                    <h4>Total Sell Return</h4>
                                    <p><b>{{number_format($sum_sale_return_price, 2, '.', ',')}}</b></p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="widget-small warning coloured-icon"><i class="icon fa fa-files-o fa-3x"></i>
                                <div class="info">
                                    <h4>Last 30 Day Sell Return</h4>
                                    <p><b>{{number_format($sum_last_thirty_day_sale_return_price, 2, '.', ',')}}</b></p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="widget-small warning coloured-icon"><i class="icon fa fa-files-o fa-3x"></i>
                                <div class="info">
                                    <h4>Today Sell Return</h4>
                                    <p><b>{{number_format($sum_today_sale_return_price, 2, '.', ',')}}</b></p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="widget-small info coloured-icon"><i class="icon fab fa-paypal " ></i><div class="info">
                                    <h4> Total Loss/Profit</h4>
                                    <p>
                                        <b>
                                            @if(abs($sum_loss_or_profit) > 0)
                                                Profit: {{number_format($sum_loss_or_profit, 2, '.', ',')}}
                                            @else
                                                Loss: {{number_format($sum_loss_or_profit, 2, '.', ',')}}
                                            @endif
                                        </b>
                                    </p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="widget-small info coloured-icon"><i class="icon fab fa-paypal " ></i><div class="info">
                                    <h4> Last 30 Day Loss/Profit</h4>
                                    <p>
                                        <b>
                                            @if(abs($sum_last_thirty_day_loss_or_profit) > 0)
                                                Profit: {{number_format($sum_last_thirty_day_loss_or_profit, 2, '.', ',')}}
                                            @else
                                                Loss: {{number_format($sum_last_thirty_day_loss_or_profit, 2, '.', ',')}}
                                            @endif
                                        </b>
                                    </p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="widget-small info coloured-icon"><i class="icon fab fa-paypal " ></i><div class="info">
                                    <h4> Today Loss/Profit</h4>
                                    <p>
                                        <b>
                                            @if(abs($sum_today_loss_or_profit) > 0)
                                                Profit: {{number_format($sum_today_loss_or_profit, 2, '.', ',')}}
                                            @else
                                                Loss: {{number_format($sum_today_loss_or_profit, 2, '.', ',')}}
                                            @endif
                                        </b>
                                    </p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="widget-small primary coloured-icon"><i class="icon fa fa-users fa-3x"></i>
                                <div class="info">
                                    <h4>Stock Transfer OUt</h4>
                                    <p><b>{{$from_stock_transfer_sum_sub_total ? number_format($from_stock_transfer_sum_sub_total, 2, '.', ',') : number_format(0, 2, '.', ',')}}</b></p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="widget-small primary coloured-icon"><i class="icon fa fa-users fa-3x"></i>
                                <div class="info">
                                    <h4>Stock Transfer In</h4>
                                    <p><b>{{$to_stock_transfer_sum_sub_total ? number_format($to_stock_transfer_sum_sub_total, 2, '.', ',') : number_format(0, 2, '.', ',')}}</b></p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="widget-small primary coloured-icon"><i class="icon fa fa-users fa-3x"></i>
                                <div class="info">
                                    <h4>Stock Available Amount</h4>
                                    <?php
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
                                        ->where('product_purchases.store_id',$store->id)
                                        ->groupBy('product_purchase_details.product_id')
                                        ->groupBy('product_purchase_details.product_category_id')
                                        ->groupBy('product_purchase_details.product_sub_category_id')
                                        ->groupBy('product_purchase_details.product_brand_id')
                                        ->get();



                                    $sum_stock_purchase_price = $sum_amount;
                                    if(!empty($productPurchasesSummations)){
                                        foreach($productPurchasesSummations as $key => $productPurchasesSummation){
                                            $purchase_average_price = $productPurchasesSummation->sub_total/$productPurchasesSummation->qty;
                                            //$purchase_total_qty = $productPurchasesSummation->qty;


                                            // sale total
                                            $productSaleDetails = DB::table('product_sale_details')
                                                ->join('product_sales','product_sale_details.product_sale_id','=','product_sales.id')
                                                ->select('product_sale_details.product_id','product_sale_details.product_category_id','product_sale_details.product_sub_category_id','product_sale_details.product_brand_id', DB::raw('SUM(qty) as qty'), DB::raw('SUM(price) as price'), DB::raw('SUM(sub_total) as sub_total'))
                                                ->where('product_sale_details.product_id',$productPurchasesSummation->product_id)
                                                ->where('product_sale_details.product_category_id',$productPurchasesSummation->product_category_id)
                                                ->where('product_sale_details.product_sub_category_id',$productPurchasesSummation->product_sub_category_id)
                                                ->where('product_sale_details.product_brand_id',$productPurchasesSummation->product_brand_id)
                                                ->where('product_sales.store_id',$store->id)
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
                                    ?>
                                    <p><b>{{$sum_stock_purchase_price ? number_format($sum_stock_purchase_price, 2, '.', ',') : number_format(0, 2, '.', ',')}}</b></p>
                                </div>
                            </div>
                        </div>

                        {{--<div class="col-md-3">
                            <div class="widget-small warning coloured-icon"><i class="icon fas fa-file-invoice-dollar"></i>
                                <div class="info">
                                    <h4>Today Due</h4>
                                    <p><b></b></p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="widget-small info coloured-icon"><i class="icon fas fa-file-invoice"></i> <div class="info">
                                    <h4>Today Invoice</h4>
                                    <p><b></b></p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="widget-small info coloured-icon"><i class="icon fas fa-file-invoice"></i> <div class="info">
                                    <h4>Today Invoice</h4>
                                    <p><b></b></p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="widget-small info coloured-icon"><i class="icon fas fa-file-invoice"></i> <div class="info">
                                    <h4>Today Invoice</h4>
                                    <p><b></b></p>
                                </div>
                            </div>
                        </div>--}}
                    @endforeach
                @endif
        </div>
        @else
            <h1>
                Only Admin can show At a Glance! User can only Sale permission.
                <a href="{!! route('productSales.create') !!}" class="btn btn-sm btn-primary" type="button">Add Product Sales</a>
            </h1>
        @endif
    </main>
@endsection


@section('footer')

@endsection

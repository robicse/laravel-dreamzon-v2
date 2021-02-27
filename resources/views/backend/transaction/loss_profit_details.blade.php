@extends('backend._partial.dashboard')

@section('content')
    <main class="app-content">
        <div class="app-title">
            <div>
                <h1><i class=""></i>Loss/Profit</h1>
            </div>
{{--            <ul class="app-breadcrumb breadcrumb">--}}
{{--                <li class="breadcrumb-item"><a class="btn btn-warning" href="{{ route('loss.profit.export') }}">Export Data</a></li>--}}
{{--            </ul>--}}
        </div>
        <div class="col-md-12">
            <div class="tile">
                <h3 class="tile-title">Loss/Profit</h3>
                <form class="form-inline" action="{{ URL('transaction-loss-profit-details/'.$store_id) }}">
                    <div class="form-group col-md-3">
                        <label for="start_date">Start Date:</label>
                        <input type="text" name="start_date" class="datepicker form-control" value="{{$start_date}}">
                    </div>
                    <div class="form-group col-md-3">
                        <label for="end_date">End Date:</label>
                        <input type="text" name="end_date" class="datepicker form-control" value="{{$end_date}}">
                    </div>
                    <div class="form-group col-md-3">
                        <button type="submit" class="btn btn-success">Submit</button>
                        <a href="{!! URL('transaction-loss-profit-details/'.$store_id) !!}" class="btn btn-primary" type="button">Reset</a>
                    </div>
                </form>
                <?php
                    $store = DB::table('stores')->where('id',$store_id)->first();
                ?>
                <div class="col-md-12">
                    <h1 class="text-center">{{$store->name}}</h1>
                    <div class="table-responsive">
                        <table id="example1" class="table table-bordered table-striped">
                            <thead>
                            <tr>
                                <th width="15%">Product</th>
                                <th width="15%">Purchase Qty</th>
                                <th width="15%">Total Purchase Price</th>
                                <th width="15%">Average Purchase Price</th>
                                <th width="15%">Sale Qty</th>
                                <th width="15%">Total Sale Price</th>
                                <th width="15%">Average Sale Price</th>
                                <th width="15%">Sale Return Qty</th>
                                <th width="15%">Total Sale Return Price</th>
                                <th width="15%">Average Sale Return Price</th>
                                <th width="15%">Loss/Profit</th>
                            </tr>
                            </thead>
                            <tbody>
                            @php
                                $productPurchaseDetails = DB::table('product_purchase_details')
                                    ->join('product_purchases','product_purchases.id','=','product_purchase_details.product_purchase_id')
                                    ->select('product_id','product_category_id','product_sub_category_id','product_brand_id', DB::raw('SUM(qty) as qty'), DB::raw('SUM(price) as price'), DB::raw('SUM(sub_total) as sub_total'))
                                    ->where('product_purchases.store_id',$store->id)
                                    ->groupBy('product_id')
                                    ->groupBy('product_category_id')
                                    ->groupBy('product_sub_category_id')
                                    ->groupBy('product_brand_id')
                                    ->get();


                                $sum_loss_or_profit = 0;
                            @endphp
                            @foreach($productPurchaseDetails as $key => $productPurchaseDetail)
                                @php
                                    $loss_or_profit = 0;
                                    $current_loss_or_profit = 0;
                                    $sale_total_qty = 0;
                                @endphp
                                <tr>
                                    <td>
                                        @php
                                            echo $product_name = \App\Product::where('id',$productPurchaseDetail->product_id)->pluck('name')->first();
                                        @endphp
                                    </td>
                                    <td>{{$productPurchaseDetail->qty}}</td>
                                    <td>{{$productPurchaseDetail->sub_total}}</td>
                                    <td>{{$purchase_average_price = $productPurchaseDetail->sub_total/$productPurchaseDetail->qty}}</td>


                                    @php
                                        // sale
                                        $sale_total_qty = 0;
                                        $sale_total_amount = 0;
                                        $sale_average_price = 0;

                                        if($start_date && $end_date){
                                            $productSaleDetails = DB::table('product_sale_details')
                                            ->join('product_sales','product_sale_details.product_sale_id','=','product_sales.id')
                                            ->select('product_sale_details.product_id','product_sale_details.product_category_id','product_sale_details.product_sub_category_id','product_sale_details.product_brand_id', DB::raw('SUM(qty) as qty'), DB::raw('SUM(price) as price'), DB::raw('SUM(sub_total) as sub_total'))
                                            ->where('product_sale_details.product_id',$productPurchaseDetail->product_id)
                                            ->where('product_sale_details.product_category_id',$productPurchaseDetail->product_category_id)
                                            ->where('product_sale_details.product_sub_category_id',$productPurchaseDetail->product_sub_category_id)
                                            ->where('product_sale_details.product_brand_id',$productPurchaseDetail->product_brand_id)
                                            ->where('product_sales.store_id',$store->id)
                                            ->where('product_sales.date','>=',$start_date)
                                            ->where('product_sales.date','<=',$end_date)
                                            ->groupBy('product_sale_details.product_id')
                                            ->groupBy('product_sale_details.product_category_id')
                                            ->groupBy('product_sale_details.product_sub_category_id')
                                            ->groupBy('product_sale_details.product_brand_id')
                                            ->first();
                                        }else{
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
                                        }

                                        if(!empty($productSaleDetails))
                                        {
                                            $sale_total_qty = $productSaleDetails->qty;
                                            $sale_total_amount = $productSaleDetails->sub_total;
                                            $sale_average_price = $productSaleDetails->sub_total/$productSaleDetails->qty;

                                            if($sale_total_qty > 0){
                                                $loss_or_profit = ($sale_average_price*$sale_total_qty) - ($purchase_average_price*$sale_total_qty);
                                                $current_loss_or_profit += $loss_or_profit;
                                                $sum_loss_or_profit += $loss_or_profit;
                                            }
                                        }

                                        // sale return
                                        $sale_return_total_qty = 0;
                                        $sale_return_total_amount = 0;
                                        $sale_return_average_price = 0;

                                        if($start_date && $end_date){
                                            $productSaleReturnDetails = DB::table('product_sale_return_details')
                                            ->select('product_id','product_category_id','product_sub_category_id','product_brand_id', DB::raw('SUM(qty) as qty'), DB::raw('SUM(price) as price'))
                                            ->where('product_id',$productPurchaseDetail->product_id)
                                            ->where('product_category_id',$productPurchaseDetail->product_category_id)
                                            ->where('product_sub_category_id',$productPurchaseDetail->product_sub_category_id)
                                            ->where('product_brand_id',$productPurchaseDetail->product_brand_id)
                                            ->where('product_sale_return_details.created_at','>=',$start_date.' 00:00:00')
                                            ->where('product_sale_return_details.created_at','<=',$end_date.' 23:59:59')
                                            ->groupBy('product_id')
                                            ->groupBy('product_category_id')
                                            ->groupBy('product_sub_category_id')
                                            ->groupBy('product_brand_id')
                                            ->first();
                                        }else{
                                            $productSaleReturnDetails = DB::table('product_sale_return_details')
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
                                        }

                                        if(!empty($productSaleReturnDetails))
                                        {
                                            $sale_return_total_qty = $productSaleReturnDetails->qty;
                                            $sale_return_total_amount = $productSaleReturnDetails->price;
                                            $sale_return_average_price = $sale_return_total_amount/$productSaleReturnDetails->qty;

                                            if($sale_return_total_qty > 0){
                                                $loss_or_profit = $sale_return_average_price - ($purchase_average_price*$sale_return_total_qty);
                                                $current_loss_or_profit -= $loss_or_profit;
                                                $sum_loss_or_profit -= $loss_or_profit;
                                            }
                                        }
                                    @endphp
                                    <td>
                                        {{$sale_total_qty}}
                                    </td>
                                    <td>{{$sale_total_amount}}</td>
                                    <td>{{$sale_average_price}}</td>
                                    <td>{{$sale_return_total_qty}}</td>
                                    <td>{{$sale_return_total_amount}}</td>
                                    <td>{{$sale_return_average_price}}</td>
                                    <td>{{$current_loss_or_profit}}</td>

                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>

                    <table>
                        <thead>
                        <tr>
                            <th colspan="10">Sum Loss/Profit: </th>
                            <th>
                                @if($sum_loss_or_profit > 0)
                                    Profit: {{number_format($sum_loss_or_profit, 2, '.', '')}}
                                @else
                                    Loss: {{number_format($sum_loss_or_profit, 2, '.', '')}}
                                @endif
                            </th>
                        </tr>
                        </thead>
                    </table>
                    <div class="tile-footer">
                    </div>
                </div>
            </div>

        </div>
    </main>
@endsection



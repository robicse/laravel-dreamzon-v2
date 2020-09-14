@if(!empty($stores))
    @foreach($stores as $store)
<table class="table table-bordered mt-3">
    <thead>
    <tr>
        <th>{{$store->name}}</th>
    </tr>
    <tr>
        <th>Product</th>
        <th>Purchase Qty</th>
        <th>Total Purchase Price</th>
        <th>Average Purchase Price</th>
        <th>Sale Qty</th>
        <th>Total Sale Price</th>
        <th>Average Sale Price</th>
        <th>Sale Return Qty</th>
        <th>Total Sale Return Price</th>
        <th>Average Sale Return Price</th>
        <th>Loss/Profit</th>
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
            {{--                                        <td>--}}
            {{--                                            @php--}}
            {{--                                                echo $product_category_name = \App\ProductCategory::where('id',$productPurchaseDetail->product_category_id)->pluck('name')->first();--}}
            {{--                                            @endphp--}}
            {{--                                        </td>--}}
            {{--                                        <td>--}}
            {{--                                            @php--}}
            {{--                                                echo $product_sub_category_name = \App\ProductSubCategory::where('id',$productPurchaseDetail->product_sub_category_id)->pluck('name')->first();--}}
            {{--                                            @endphp--}}
            {{--                                        </td>--}}
            {{--                                        <td>--}}
            {{--                                            @php--}}
            {{--                                                echo $product_brand_name = \App\ProductBrand::where('id',$productPurchaseDetail->product_brand_id)->pluck('name')->first();--}}
            {{--                                            @endphp--}}
            {{--                                        </td>--}}
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

                $productSaleDetails = DB::table('product_sale_details')
                    ->select('product_id','product_category_id','product_sub_category_id','product_brand_id', DB::raw('SUM(qty) as qty'), DB::raw('SUM(price) as price'), DB::raw('SUM(sub_total) as sub_total'))
                    ->where('product_id',$productPurchaseDetail->product_id)
                    ->where('product_category_id',$productPurchaseDetail->product_category_id)
                    ->where('product_sub_category_id',$productPurchaseDetail->product_sub_category_id)
                    ->where('product_brand_id',$productPurchaseDetail->product_brand_id)
                    ->groupBy('product_id')
                    ->groupBy('product_category_id')
                    ->groupBy('product_sub_category_id')
                    ->groupBy('product_brand_id')
                    ->first();

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
<table>
    <thead>
    <tr>
        <th colspan="10">Sum Loss/Profit: </th>
        <th>
            @if($sum_loss_or_profit > 0)
                Profit: {{$sum_loss_or_profit}}
            @else
                Loss: {{$sum_loss_or_profit}}
            @endif
        </th>
    </tr>
    </thead>
</table>
    @endforeach
@endif

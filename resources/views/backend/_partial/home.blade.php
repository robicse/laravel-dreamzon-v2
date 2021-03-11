@extends('backend._partial.dashboard')

@section('content')
    <main class="app-content">
        <div class="app-title">
            <div>
                <h1><i class="fa fa-dashboard"></i> Dashboard {{store_test(2)}} </h1>
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

                        <div class="col-md-4 ">
                            <div class="widget-small primary coloured-icon"><i class="icon fa fa-users fa-3x"></i>
                                <div class="info">
                                    <h4>Total Purchase</h4>
                                    <p><b>{{number_format(sum_total_purchase_amount($store->id), 2, '.', ',')}}</b></p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4 ">
                            <div class="widget-small primary coloured-icon"><i class="icon fa fa-users fa-3x"></i>
                                <div class="info">
                                    <h4>Last 30 Day Purchase</h4>
                                    <p><b>{{number_format(sum_last_thirty_day_purchase_amount($store->id), 2, '.', ',')}}</b></p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4 ">
                            <div class="widget-small primary coloured-icon"><i class="icon fa fa-users fa-3x"></i>
                                <div class="info">
                                    <h4>Today Purchase</h4>
                                    <p><b>{{number_format(sum_today_purchase_amount($store->id), 2, '.', ',')}}</b></p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="widget-small danger coloured-icon"><i class="icon fas fa-money-check-alt "></i>
                                <div class="info">
                                    <h4>Total Sell</h4>
                                    <p><b>{{number_format(sum_sale_price($store->id), 2, '.', ',')}}</b></p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="widget-small danger coloured-icon"><i class="icon fas fa-money-check-alt "></i>
                                <div class="info">
                                    <h4>Last 30 Day Sell</h4>
                                    <p><b>{{number_format(sum_last_thirty_day_sale_price($store->id), 2, '.', ',')}}</b></p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="widget-small danger coloured-icon"><i class="icon fas fa-money-check-alt "></i>
                                <div class="info">
                                    <h4>Today Sell</h4>
                                    <p><b>{{number_format(sum_today_sale_price($store->id), 2, '.', ',')}}</b></p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="widget-small warning coloured-icon"><i class="icon fa fa-files-o fa-3x"></i>
                                <div class="info">
                                    <h4>Total Sell Return</h4>
                                    <p><b>{{number_format(sum_sale_return_price($store->id), 2, '.', ',')}}</b></p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="widget-small warning coloured-icon"><i class="icon fa fa-files-o fa-3x"></i>
                                <div class="info">
                                    <h4>Last 30 Day Sell Return</h4>
                                    <p><b>{{number_format(sum_last_thirty_day_sale_return_price($store->id), 2, '.', ',')}}</b></p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="widget-small warning coloured-icon"><i class="icon fa fa-files-o fa-3x"></i>
                                <div class="info">
                                    <h4>Today Sell Return</h4>
                                    <p><b>{{number_format(sum_today_sale_return_price($store->id), 2, '.', ',')}}</b></p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="widget-small info coloured-icon"><i class="icon fab fa-paypal " ></i><div class="info">
                                    <h4> Total Loss/Profit</h4>
                                    <p>
                                        <b>
                                            @php
                                                $sum_loss_or_profit = sum_loss_or_profit($store->id)
                                            @endphp
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
                                            @php
                                                $sum_last_thirty_day_loss_or_profit = sum_last_thirty_day_loss_or_profit($store->id)
                                            @endphp
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
                                            @php
                                                $sum_today_loss_or_profit = sum_today_loss_or_profit($store->id)
                                            @endphp
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
                                    @php
                                        $from_stock_transfer_sum_sub_total = from_stock_transfer_sum_sub_total($store->id);
                                    @endphp
                                    <p><b>{{$from_stock_transfer_sum_sub_total ? number_format($from_stock_transfer_sum_sub_total, 2, '.', ',') : number_format(0, 2, '.', ',')}}</b></p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="widget-small primary coloured-icon"><i class="icon fa fa-users fa-3x"></i>
                                <div class="info">
                                    <h4>Stock Transfer In</h4>
                                    @php
                                        $to_stock_transfer_sum_sub_total = to_stock_transfer_sum_sub_total($store->id);
                                    @endphp
                                    <p><b>{{$to_stock_transfer_sum_sub_total ? number_format($to_stock_transfer_sum_sub_total, 2, '.', ',') : number_format(0, 2, '.', ',')}}</b></p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="widget-small primary coloured-icon"><i class="icon fa fa-users fa-3x"></i>
                                <div class="info">
                                    <h4>Stock Available Amount</h4>
                                    <?php
                                        $sum_stock_purchase_price = sum_stock_purchase_price($store->id);
                                    ?>
                                    <p><b>{{$sum_stock_purchase_price ? number_format($sum_stock_purchase_price, 2, '.', ',') : number_format(0, 2, '.', ',')}}</b></p>
                                </div>
                            </div>
                        </div>
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

@extends('backend._partial.dashboard')

@section('content')
    <main class="app-content">
        <div class="app-title">
            <div>
                <h1><i class=""></i> Product Sales And Details</h1>
            </div>
            <ul class="app-breadcrumb breadcrumb">
                <li class="breadcrumb-item"> <a href="{!! route('productSales.index') !!}" class="btn btn-sm btn-primary" type="button">Back</a></li>
            </ul>
        </div>
        <div class="col-md-12">
            <div class="tile">
                <ul class="app-breadcrumb breadcrumb">
                    <li class="breadcrumb-item" style="margin-left: 90%"> <a href="{!! route('productSales-invoice',$productSale->id) !!}" class="btn btn-sm btn-primary"  type="button">Print Page</a></li>
                </ul>
                <h3 class="tile-title">Product Sales</h3>
                <div class="table-responsive">
                    <table id="example1" class="table table-bordered table-striped">
                    <thead>
                    <tr>
                    </tr>
                    </thead>
                    <tbody>
                    <tr>
                        <th>User</th>
                        <td>{{$productSale->user_name}}</td>
                    </tr>
                    <tr>
                        <th>Store</th>
                        <td>{{$productSale->store_name}}</td>
                    </tr>
                    <tr>
                        <th>Party</th>
                        <td>{{$productSale->party_name}}</td>
                    </tr>
                    <tr>
                        <th>Payment Type</th>
                        <td>{{$productSale->payment_type}}</td>
                    </tr>
{{--                    <tr>--}}
{{--                        <th>Delivery Service</th>--}}
{{--                        <td>{{$productSale->deliveryService->name}}</td>--}}
{{--                    </tr>--}}
                    <tr>
                        <th>Discount Type</th>
                        <td>{{$productSale->discount_type}}</td>
                    </tr>
                    <tr>
                        <th>Discount Amount</th>
                        <td>{{$productSale->discount_amount}}</td>
                    </tr>
                    <tr>
                        <th>Total Amount</th>
                        <td>{{$productSale->total_amount}}</td>
                    </tr>
                    <tr>
                        <th>Paid Amount</th>
                        <td>{{$productSale->paid_amount}}</td>
                    </tr>
                    <tr>
                        <th>Due Amount</th>
                        <td>{{$productSale->due_amount}}</td>
                    </tr>
                    </tbody>
                </table>
                    <div class="tile-footer">
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-12">
            <div class="tile">
                <h3 class="tile-title">Product Sales Details</h3>
                <table class="table table-striped">
                    <thead>
                    <tr>
                        <th>Category</th>
{{--                        <th>Sub Category</th>--}}
                        <th>Brand</th>
                        <th>Return Condition</th>
                        <th>Product Image</th>
                        <th>Barcode</th>
                        <th>Product</th>
                        <th>Qty</th>
                        <th>Price</th>
                        <th>Sub Total</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($productSaleDetails as $productSaleDetail)
                        <tr>
                            <td>{{$productSaleDetail->product->product_category->name}}</td>
{{--                            <td>--}}
{{--                                {{$productSaleDetail->product->product_sub_category ? $productSaleDetail->product->product_sub_category->name : ''}}--}}
{{--                            </td>--}}
                            <td>{{$productSaleDetail->product->product_brand->name}}</td>
                            <td>{{$productSaleDetail->return_type}}</td>
                            <td>
                                <img src="{{asset('uploads/product/'.$productSaleDetail->product->image)}}" width="50" height="50" />
                            </td>
                            <td>{{$productSaleDetail->product->barcode}}</td>
                            <td>{{$productSaleDetail->product->name}}</td>
                            <td>{{$productSaleDetail->qty}}</td>
                            <td>{{$productSaleDetail->price}}</td>
                            <td>{{$productSaleDetail->sub_total}}</td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
                <div class="tile-footer">
                </div>
            </div>
        </div>
    </main>
@endsection



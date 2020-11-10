@extends('backend._partial.dashboard')

@section('content')
    <main class="app-content">
        <div class="app-title">
            <div>
                <h1><i class=""></i> Product Expired Details</h1>
            </div>
            <ul class="app-breadcrumb breadcrumb">
                <li class="breadcrumb-item"> <a href="{!! route('productExpireds.index') !!}" class="btn btn-sm btn-primary" type="button">Back</a></li>
            </ul>
        </div>
        <div class="col-md-12">
            <div class="tile">
                <h3 class="tile-title">Product Expired</h3>
                <table class="table table-striped">
                    <thead>
                    <tr>
                    </tr>
                    </thead>
                    <tbody>
                    <tr>
                        <th>User</th>
                        <td>{{$productExpireds->user->name}}</td>
                    </tr>
                    <tr>
                        <th>Store</th>
                        <td>{{$productExpireds->store->name}}</td>
                    </tr>
                    <tr>
                        <th>Product Category</th>
                        <td>{{$productExpireds->productCategory->name}}</td>
                    </tr>
                    <tr>
                        <th>Product Sub Category</th>
                        <td>{{$productExpireds->productSubCategory ? $productExpireds->productSubCategory->name : ''}}</td>
                    </tr>
                    <tr>
                        <th>Product Brand</th>
                        <td>{{$productExpireds->productBrand->name}}</td>
                    </tr>
                    <tr>
                        <th>Barcode</th>
                        <td>{{$productExpireds->barcode}}</td>
                    </tr>
                    <tr>
                        <th>Product</th>
                        <td>{{$productExpireds->product->name}}</td>
                    </tr>
                    <tr>
                        <th>Expired Qty</th>
                        <td>{{$productExpireds->qty}}</td>
                    </tr>
                    <tr>
                        <th>Price</th>
                        <td>{{$productExpireds->price}}</td>
                    </tr>
                    <tr>
                        <th>Total</th>
                        <td>{{$productExpireds->total}}</td>
                    </tr>
                    </tbody>
                </table>
                <div class="tile-footer">
                </div>
            </div>
        </div>
    </main>
@endsection



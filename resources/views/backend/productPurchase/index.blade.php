@extends('backend._partial.dashboard')

@section('content')
    <main class="app-content">
        <div class="app-title">
            <div>
                <h1><i class=""></i> All Product Purchases</h1>
            </div>
            <ul class="app-breadcrumb breadcrumb">
                <li class="breadcrumb-item"> <a href="{!! route('productPurchases.create') !!}" class="btn btn-sm btn-primary" type="button">Add Product Purchases</a></li>
            </ul>
        </div>
        <div class="col-md-12">
            <div class="tile">
                <h3 class="tile-title">Product Purchases Table</h3>
                {{--<form method="post" action="{{ URL('purchaseorders/find_purchase_order') }}">--}}
                <form method="get" action="" class="form-inline">
                    {{--@csrf--}}
                    <div class="form-group">
                        <input type="text" class="bg-light text-dark form-control"  placeholder="Invoice NO" value="{{$invoice_no}}" name="invoice_no"  aria-label="Username" >
                    </div>
                    <div class="form-group">
                        <select class="form-control select2" name="party_id">
                            <option value="">Select Party</option>
                            @foreach($parties as $party_data)
                                <option value="{{$party_data->id}}" {{$party_id == $party_data->id ? 'selected' : ''}}>{{$party_data->name}}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <select class="form-control select2" name="product_id">
                            <option value="">Select Product</option>
                            @foreach($products as $product)
                                <option value="{{$product->id}}" {{$product_id == $product->id ? 'selected' : ''}}>{{$product->name}}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <button class="btn btn-sm btn-primary float-left p-2">Advanced Search</button><span>&nbsp;</span>
                        <a href="{{ route('productPurchases.index') }}" class="btn btn-sm btn-info float-right p-2" role="button">Reset</a>
                    </div>
                </form>
                <br/>

                 <table id="example1" class="table table-bordered table-striped">
                    <thead>
                    <tr>

                    </tr>
                    <tr>
                        <th width="5%">#Id</th>
                        <th>Invoice NO</th>
                        <th>Date</th>
                        <th>Party</th>
                        <th>Phone</th>
                        <th>Payment Type</th>
                        <th>Total Amount</th>
                        <th>Action</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($productPurchases as $key => $productPurches)
                    <tr>
                        <td>{{ $key+1 }}</td>
                        <td>{{ $productPurches->invoice_no}}</td>
                        <td>{{ $productPurches->created_at}}</td>
                        <td>{{ $productPurches->party->name}}</td>
                        <td>{{ $productPurches->party->phone}}</td>
                        <td>{{ $productPurches->payment_type}}</td>
                        <td>{{ $productPurches->total_amount}}</td>
                        <td class="d-inline-flex">
                            <a href="{{ route('productPurchases.show',$productPurches->id) }}" class="btn btn-sm btn-info float-left">Show</a>
                            <a href="{{ route('productPurchases.edit',$productPurches->id) }}" class="btn btn-sm btn-primary float-left"><i class="fa fa-edit"></i></a>
                            <form method="post" action="{{ route('productPurchases.destroy',$productPurches->id) }}" >
                               @method('DELETE')
                                @csrf
                                <button class="btn btn-sm btn-danger" type="submit" onclick="return confirm('You Are Sure This Delete !')"><i class="fa fa-trash"></i></button>
                            </form>
                        </td>
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



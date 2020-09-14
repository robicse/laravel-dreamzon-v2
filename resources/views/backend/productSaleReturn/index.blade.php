@extends('backend._partial.dashboard')

@section('content')
    <main class="app-content">
        <div class="app-title">
            <div>
                <h1><i class=""></i> All Sale Return Product</h1>
            </div>
{{--            <ul class="app-breadcrumb breadcrumb">--}}
{{--                <li class="breadcrumb-item"> <a href="{!! route('productSales.create') !!}" class="btn btn-sm btn-primary" type="button">Add Product Sales</a></li>--}}
{{--            </ul>--}}
        </div>
        <div class="col-md-12">
            <div class="tile">

                <h3 class="tile-title">Sale Return Product Table</h3>
                <table id="example1" class="table table-bordered table-striped">

                    <thead>
                    <tr>
                        <th width="5%">#Id</th>
                        <th>User</th>
                        <th>Store</th>
                        <th>Party</th>
                        <th>Payment Type</th>
                        <th>Total Amount</th>
                        <th>Action</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($productSaleReturns as $key => $productSaleReturn)
                    <tr>
                        <td>{{ $key+1 }}</td>
                        <td>{{ $productSaleReturn->user->name}}</td>
                        <td>{{ $productSaleReturn->store->name}}</td>
                        <td>{{ $productSaleReturn->party->name}}</td>
                        <td>{{ $productSaleReturn->payment_type}}</td>
                        <td>{{ $productSaleReturn->total_amount}}</td>
                        <td>
                            <a href="{{ route('productSaleReturns.show',$productSaleReturn->id) }}" class="btn btn-sm btn-info float-left">Show</a>
                        </td>
                    </tr>
                        @endforeach
                    </tbody>
                </table>
                <div class="tile-footer">
                </div>
{{--                {{ $parties->links() }}--}}
            </div>

        </div>
    </main>
@endsection



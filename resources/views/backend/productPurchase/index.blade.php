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
                    @foreach($productPurchases as $key => $productPurches)
                    <tr>
                        <td>{{ $key+1 }}</td>
                        <td>{{ $productPurches->user->name}}</td>
                        <td>{{ $productPurches->store->name}}</td>
                        <td>{{ $productPurches->party->name}}</td>
                        <td>{{ $productPurches->payment_type}}</td>
                        <td>{{ $productPurches->total_amount}}</td>
                        <td>
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
{{--                {{ $parties->links() }}--}}
            </div>

        </div>
    </main>
@endsection



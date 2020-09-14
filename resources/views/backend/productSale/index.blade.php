@extends('backend._partial.dashboard')

@section('content')
    <main class="app-content">
        <div class="app-title">
            <div>
                <h1><i class=""></i> All Product Sale</h1>
            </div>
            <ul class="app-breadcrumb breadcrumb">
                <li class="breadcrumb-item"> <a href="{!! route('productSales.create') !!}" class="btn btn-sm btn-primary" type="button">Add Product Sales</a></li>
            </ul>
        </div>
        <div class="col-md-12">
            <div class="tile">

                <h3 class="tile-title">Product Sales Table</h3>
                <table id="example1" class="table table-bordered table-striped">

                    <thead>
                    <tr>
                        <th width="5%">#Id</th>
                        <th>User</th>
                        <th>Store</th>
                        <th>Party</th>
                        <th>Payment Type</th>
                        <th>Total Amount</th>
                        <th>Paid Amount</th>
                        <th>Due Amount</th>
                        <th>Action</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($productSales as $key => $productSale)
                    <tr>
                        <td>{{ $key+1 }}</td>
                        <td>{{ $productSale->user->name}}</td>
                        <td>{{ $productSale->store->name}}</td>
                        <td>{{ $productSale->party->name}}</td>
                        <td>{{ $productSale->payment_type}}</td>
                        <td>{{ $productSale->total_amount}}</td>
                        <td>{{ $productSale->paid_amount}}</td>
                        <td>
                            {{ $productSale->due_amount}}
                            @if($productSale->total_amount != $productSale->paid_amount)
                                <a href="" class="btn btn-warning btn-sm mx-1" data-toggle="modal" data-target="#exampleModal-<?= $productSale->id;?>"> Pay Due</a>
                            @endif
                        </td>
                        <td>
                            <a href="{{ route('productSales.show',$productSale->id) }}" class="btn btn-sm btn-info float-left">Show</a>
                            <a href="{{ route('productSales.edit',$productSale->id) }}" class="btn btn-sm btn-primary float-left"><i class="fa fa-edit"></i></a>
                            <form method="post" action="{{ route('productSales.destroy',$productSale->id) }}" >
                               @method('DELETE')
                                @csrf
                                <button class="btn btn-sm btn-danger" type="submit" onclick="return confirm('You Are Sure This Delete !')"><i class="fa fa-trash"></i></button>
                            </form>
                        </td>
                    </tr>
                    <!-- Modal -->
                    <div class="modal fade" id="exampleModal-{{$productSale->id}}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                        <div class="modal-dialog" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="exampleModalLabel">Pay Due</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body">
                                    <form action="{{route('pay.due')}}" method="post">
                                        @csrf
                                        <div class="form-group">
                                            <label for="due">Enter Due Amount</label>
                                            <input type="hidden" class="form-control" name="product_sale_id" value="{{$productSale->id}}">
                                            <input type="number" class="form-control" id="due" aria-describedby="emailHelp" name="new_paid" min="" max="{{$productSale->due_amount}}" placeholder="Enter Amount">
                                        </div>
                                        <div class="form-group">
                                            <button type="submit" class="btn btn-primary">Submit</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
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



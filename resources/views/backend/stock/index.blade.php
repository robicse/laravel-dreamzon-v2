@extends('backend._partial.dashboard')

@section('content')
    <main class="app-content">
        <div class="app-title">
            <div>
                <h1><i class=""></i> All Stock</h1>
            </div>
            <ul class="app-breadcrumb breadcrumb">
                <li class="breadcrumb-item"><a class="btn btn-warning" href="{{ route('stock.export') }}">Export Data</a></li>
            </ul>
        </div>
        <div class="col-md-12">
            <div class="tile">
                <h3 class="tile-title">Stock Table</h3>
                @if(!empty($stores))
                    @foreach($stores as $store)
                        <div class="col-md-12">
                            <h1 class="text-center">{{$store->name}}</h1>
                        </div>
                        <table id="example1" class="table table-bordered table-striped">
                            <thead>
                            <tr>
                                <th width="5%">#Id</th>
                                <th width="10%">Store</th>
                                <th width="15%">Stock Type</th>
                                <th width="15%">Product</th>
                                <th width="15%">Previous Stock</th>
                                <th width="15%">Stock In</th>
                                <th width="15%">Stock Out</th>
                                <th width="15%">Current Stock</th>
                            </tr>
                            </thead>
                            <tbody>
                            @php
                                $stocks = \App\Stock::where('store_id',$store->id)->get();
                            @endphp
                            @foreach($stocks as $key => $stock)
                                <tr>
                                    <td>{{ $key+1 }}</td>
                                    <td>{{ $stock->store->name}}</td>
                                    <td>{{ $stock->stock_type}}</td>
                                    <td>{{ $stock->product->name}}</td>
                                    <td>{{ $stock->previous_stock}}</td>
                                    <td>{{ $stock->stock_in}}</td>
                                    <td>{{ $stock->stock_out}}</td>
                                    <td>{{ $stock->current_stock}}</td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                        <div class="tile-footer">
                        </div>
                    @endforeach
                @endif
            </div>

        </div>
    </main>
@endsection



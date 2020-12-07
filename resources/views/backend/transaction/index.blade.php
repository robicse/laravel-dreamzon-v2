@extends('backend._partial.dashboard')

@section('content')
    <main class="app-content">
        <div class="app-title">
            <div>
                <h1><i class=""></i> All Transaction</h1>
            </div>
        </div>
        <div class="col-md-12">
            <div class="tile">
                <div>
                    <h1><i class=""></i> All Transaction</h1>
                </div>
                <ul class="app-breadcrumb breadcrumb">
                    <li class="breadcrumb-item"><a class="btn btn-warning" href="{{ route('transaction.export') }}">Export Data</a></li>
                </ul>
                @if(!empty($stores))
                    @foreach($stores as $key => $store)
                        <div class="col-md-12">
                            <h1 class="text-center">{{$store->name}}</h1>
                        </div>
                        <table id="example{{$key + 1}}" class="table table-bordered table-striped">
                            <thead>
                            <tr>
                                <th width="5%">#Id</th>
                                <th width="10%">Invoice</th>
                                <th width="10%">User</th>
                                <th width="10%">Store</th>
                                <th width="15%">Party</th>
                                <th width="15%">Transaction Type</th>
                                <th width="15%">Payment Type</th>
                                <th width="15%">Amount</th>
                                <th width="15%">Date</th>
                            </tr>
                            </thead>
                            <tbody>
                            @php
                                $transactions = \App\Transaction::where('store_id',$store->id)->get();
                            @endphp
                            @if(!empty($transactions))
                                @foreach($transactions as $key => $transaction)
                                    <tr>
                                        <td>{{ $key+1 }}</td>
                                        <td>{{ $transaction->invoice_no}}</td>
                                        <td>{{ $transaction->user->name}}</td>
                                        <td>{{ $transaction->store->name}}</td>
                                        <td>{{ $transaction->party->name}}</td>
                                        <td>{{ $transaction->transaction_type}}</td>
                                        <td>{{ $transaction->payment_type}}</td>
                                        <td>{{ $transaction->amount}}</td>
                                        <td>{{ $transaction->date}}</td>
                                    </tr>
                                @endforeach
                            @endif
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



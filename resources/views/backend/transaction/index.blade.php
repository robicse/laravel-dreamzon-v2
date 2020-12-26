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

                <form class="form-inline" action="{{ route('transaction.index') }}">
                    <div class="form-group col-md-3">
                        <label for="start_date">Start Date:</label>
                        <input type="text" name="start_date" class="datepicker form-control" value="{{$start_date}}">
                    </div>
                    <div class="form-group col-md-3">
                        <label for="end_date">End Date:</label>
                        <input type="text" name="end_date" class="datepicker form-control" value="{{$end_date}}">
                    </div>
                    <div class="form-group col-md-3">
                        <label for="end_date">Transaction Type</label>
                        <select class="form-control" name="transaction_type">
                            <option value="">select</option>
                            <option value="purchase" {{$transaction_type == 'purchase' ? 'selected' : ''}}>Purchase</option>
                            <option value="sale" {{$transaction_type == 'sale' ? 'selected' : ''}}>Sale</option>
                        </select>
                    </div>
                    <div class="form-group col-md-3">
                        <button type="submit" class="btn btn-success">Submit</button>
                        <a href="{!! route('transaction.index') !!}" class="btn btn-primary" type="button">Reset</a>
                    </div>
                </form>

                @if(!empty($stores))
                    @foreach($stores as $key => $store)
                        <div class="col-md-12">
                            <h1 class="text-center">{{$store->name}}</h1>
                        </div>
                            <div class="table-responsive">
                                <table id="example1{{$key+1}}" class="table table-bordered table-striped">
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
                                if($start_date && $end_date && $transaction_type){
                                    $transactions = \App\Transaction::where('store_id',$store->id)->where('date','>=',$start_date)->where('date','<=',$end_date)->where('transaction_type','=',$transaction_type)->get();
                                }elseif($start_date && $end_date){
                                    $transactions = \App\Transaction::where('store_id',$store->id)->where('date','>=',$start_date)->where('date','<=',$end_date)->get();
                                }else{
                                    $transactions = \App\Transaction::where('store_id',$store->id)->get();
                                }
                            @endphp
                            @if(!empty($transactions))
                                @php
                                    $sum_amount = 0;
                                @endphp
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
                                    @php
                                        $sum_amount += $transaction->amount;
                                    @endphp
                                @endforeach
                                <tr>
                                    <td colspan="6">&nbsp;</td>
                                    <td>Total Amount:</td>
                                    <td>{{ $sum_amount }}</td>
                                </tr>
                            @endif
                            </tbody>
                        </table>
                                <div class="tile-footer">
                                </div>
                            </div>
                    @endforeach
                @endif
            </div>

        </div>
    </main>
@endsection



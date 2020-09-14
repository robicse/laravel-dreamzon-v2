@if(!empty($stores))
    @foreach($stores as $store)
<table class="table table-bordered mt-3">
    <thead>
    <tr>
        <th>{{$store->name}}</th>
    </tr>
    <tr>
        <th>ID#</th>
        <th>User</th>
        <th>Store</th>
        <th>Party</th>
        <th>Transaction Type</th>
        <th>Payment Type</th>
        <th>Amount</th>
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
                <td>{{ $transaction->user->name}}</td>
                <td>{{ $transaction->store->name}}</td>
                <td>{{ $transaction->party->name}}</td>
                <td>{{ $transaction->transaction_type}}</td>
                <td>{{ $transaction->payment_type}}</td>
                <td>{{ $transaction->amount}}</td>
            </tr>
        @endforeach
    @endif
    </tbody>
</table>
    @endforeach
@endif

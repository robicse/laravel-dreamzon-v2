<?php

namespace App\Http\Controllers;

use App\Exports\DeliveryExport;
use App\Exports\LossProfitExport;
use App\Exports\TransactionExport;
use App\Store;
use Illuminate\Http\Request;
use App\Transaction;
use App\ProductPurchaseDetail;
//use Illuminate\Support\Facades\DB;
use DB;
use Maatwebsite\Excel\Facades\Excel;

class TransactionController extends Controller
{
    function __construct()
    {
        $this->middleware('permission:product-list', ['only' => ['transactionList','lossProfit']]);
    }

    public function transactionList(){
        $stores = Store::all();

        return view('backend.transaction.index', compact('stores'));
    }

    public function transactionDetails(Request $request){
        $store_id = $request->store_id ? $request->store_id : '' ;
        $start_date = $request->start_date ? $request->start_date : '' ;
        $end_date = $request->end_date ? $request->end_date : '' ;
        $transaction_type = $request->transaction_type ? $request->transaction_type : '' ;
        return view('backend.transaction.details', compact('store_id','start_date', 'end_date','transaction_type'));
    }

    public function lossProfit(){
//       $productPurchaseDetails = DB::table('product_purchase_details')
//            ->select('product_id','product_category_id','product_sub_category_id','product_brand_id', DB::raw('SUM(qty) as qty'), DB::raw('SUM(price) as price'), DB::raw('SUM(sub_total) as sub_total'))
//            ->groupBy('product_id')
//            ->groupBy('product_category_id')
//            ->groupBy('product_sub_category_id')
//            ->groupBy('product_brand_id')
//            ->get();

//        $productPurchaseDetails = ProductPurchaseDetail::query()
//        ->select('product_id')
//        ->selectRaw('count(id) as total')
//        ->groupBy('product_id')
//        ->get();

        //dd($productPurchaseDetails);
        $stores = Store::all();
        return view('backend.transaction.loss_profit', compact('stores'));
    }

    public function transactionLossProfitDetails(Request $request){
        $store_id = $request->store_id ? $request->store_id : '' ;
        $start_date = $request->start_date ? $request->start_date : '' ;
        $end_date = $request->end_date ? $request->end_date : '' ;
        return view('backend.transaction.loss_profit_details', compact('store_id','start_date', 'end_date'));
    }

    public function deliveryList(){
        $stores = Store::all();
        return view('backend.transaction.transaction_list', compact('stores'));
    }

    public function export()
    {
        //return Excel::download(new UsersExport, 'users.xlsx');
        return Excel::download(new TransactionExport, 'transaction.xlsx');
    }

    public function deliveryExport()
    {
        return Excel::download(new DeliveryExport, 'delivery.xlsx');
    }

    public function lossProfitExport()
    {
        return Excel::download(new LossProfitExport, 'loss_profit.xlsx');
    }
}

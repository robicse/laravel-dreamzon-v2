<?php

namespace App\Http\Controllers;

use App\Exports\StockExport;
use App\Exports\TransactionExport;
use App\Stock;
use App\Store;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class StockController extends Controller
{
    function __construct()
    {
        $this->middleware('permission:product-list', ['only' => ['stockList']]);
    }
    public function stockList(){
        $stores = Store::all();
        return view('backend.stock.index', compact('stores'));
    }

    public function stockDetails($store_id){
        $stocks = Stock::where('store_id',$store_id)->latest()->get();

        return view('backend.stock.details', compact('stocks'));
    }
    public function export()
    {
        //return Excel::download(new UsersExport, 'users.xlsx');
        return Excel::download(new StockExport, 'stock.xlsx');
    }

    public function stockSummaryList(){
        $stores = Store::all();
        return view('backend.stock.stock_summary', compact('stores'));
    }

    public function stockSummary($store_id){
        $stocks = Stock::where('store_id',$store_id)
            ->whereIn('id', function($query) {
                $query->from('stocks')->groupBy('product_id')->selectRaw('MAX(id)');
            })->latest('id')->get();

        return view('backend.stock.details', compact('stocks'));
    }
}

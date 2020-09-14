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
    public function export()
    {
        //return Excel::download(new UsersExport, 'users.xlsx');
        return Excel::download(new StockExport, 'stock.xlsx');
    }
}

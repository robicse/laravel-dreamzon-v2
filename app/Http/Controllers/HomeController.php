<?php

namespace App\Http\Controllers;

use App\Store;
use Illuminate\Http\Request;
use DB;

class HomeController extends Controller
{

    /*public function __construct()
    {
        $this->middleware('auth');
    }*/

    /*function __construct()
    {
        $this->middleware('permission:home-list', ['only' => ['index']]);
    }*/


    public function index()
    {
        //return view('home');
        //return view('backend._partial.home',['customers'=>$customer,'totalDue'=>$totalDue,'todaySell'=>$todaySell,'todayDue'=>$todayDue,'todaPaid'=>$todayPaid,'todayInvoice'=>$todayInvoice]);



        $stores = Store::all();

        return view('backend._partial.home', compact('stores'));
    }
}

<?php

namespace App\Http\Controllers;

use App\Product;
use App\ProductExpired;
use App\ProductPurchase;
use App\ProductPurchaseDetail;
use App\ProductSale;
use App\ProductSaleDetail;
use App\ProductSaleReturn;
use App\ProductSaleReturnDetail;
use App\Stock;
use App\Transaction;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ProductExpiredController extends Controller
{
    public function index()
    {
        $productExpireds = ProductExpired::all();
        //dd($productSaleReturns);
        return view('backend.productExpired.index',compact('productExpireds'));
    }

    public function create()
    {
        //
    }

    public function store(Request $request)
    {
        //
    }

    public function show($id)
    {
        $productExpireds = ProductExpired::find($id);

        return view('backend.productExpired.show', compact('productExpireds'));
    }

    public function edit($id)
    {
        //
    }

    public function update(Request $request, $id)
    {
        //
    }

    public function destroy($id)
    {
        //
    }


    public function expiredProduct()
    {

//        $expired_products = '';
//
//        $stock_products = DB::table('stocks')
//            ->select('product_id')
//            ->where('current_stock','>',0)
//            ->groupBy('product_id')
//            ->get();
//
//        if(!empty($stock_products)){
//            foreach($stock_products as $stock_product){
//
//            }
//        }
//        dd($stock_products);


        //$productPurchases = ProductPurchase::latest()->get();
        $productPurchases = DB::table('product_purchase_details')
            ->select('product_purchase_details.product_id','product_purchase_details.id','product_purchase_details.expired_date','products.name')
            ->join('products','product_purchase_details.product_id','=','products.id')
            ->where('expired_date','<',date('Y-m-d'))
            ->groupBy('product_purchase_details.product_id','product_purchase_details.id','product_purchase_details.expired_date','products.name')
            ->get();
        return view('backend.productExpired.expired_products',compact('productPurchases'));
    }

//    public function returnableSaleProduct(){
//        $returnable_sale_products = ProductSaleDetail::where('return_type','returnable')->get();
//        //dd($returnable_sale_products);
//        return view('backend.productSaleReturn.returnable_sale_products',compact('returnable_sale_products'));
//    }


    public function expiredProductStore(Request $request){
        //dd($request->all());
        $product_id = $request->product_id;
        $product_purchase_detail_id = $request->product_purchase_detail_id;
        $qty = $request->qty;

        $productPurchaseDetail = ProductPurchaseDetail::find($request->product_purchase_detail_id);
        //dd($productPurchaseDetail);

        $infos = DB::table('product_purchases')
            ->join('product_purchase_details','product_purchases.id','=','product_purchase_details.product_purchase_id')
            ->join('products','product_purchase_details.product_id','=','products.id')
            ->select('product_purchases.store_id','products.barcode','product_purchases.invoice_no')
            ->where('product_purchase_id',$productPurchaseDetail->product_purchase_id)
            ->first();

        $store_id = $infos->store_id;
        $barcode = $infos->barcode;
        $invoice_no = $infos->invoice_no;


        $product_expired = new ProductExpired();
        $product_expired->invoice_no = 'expired-'.$invoice_no;
        $product_expired->user_id = Auth::id();
        $product_expired->store_id = $store_id;
        $product_expired->product_purchase_id = $productPurchaseDetail->product_purchase_id;
        $product_expired->product_purchase_detail_id = $product_purchase_detail_id;
        $product_expired->product_category_id = $productPurchaseDetail->product_category_id;
        $product_expired->product_sub_category_id = $productPurchaseDetail->product_sub_category_id;
        $product_expired->product_brand_id = $productPurchaseDetail->product_brand_id;
        $product_expired->product_id = $request->product_id;
        $product_expired->qty = $qty;
        $product_expired->price = $productPurchaseDetail->price;
        $product_expired->total = $productPurchaseDetail->price * $request->qty;
        $product_expired->barcode = $barcode;
        $product_expired->save();

        $insert_id = $product_expired->id;

        // transaction
        $transaction = new Transaction();
        $transaction->invoice_no = 'expired-'.$invoice_no;
        $transaction->user_id = Auth::id();
        $transaction->store_id = $store_id;
        $transaction->party_id = NULL;
        $transaction->ref_id = $insert_id;
        $transaction->transaction_type = 'expired';
        //$transaction->payment_type = $request->payment_type;
        $transaction->amount = $productPurchaseDetail->price * $request->qty;
        $transaction->save();

        $check_previous_stock = Stock::where('product_id',$product_id)->latest()->pluck('current_stock')->first();
        if(!empty($check_previous_stock)){
            $previous_stock = $check_previous_stock;
        }else{
            $previous_stock = 0;
        }

        // product stock
        $stock = new Stock();
        $stock->user_id = Auth::id();
        $stock->ref_id = $insert_id;
        $stock->store_id = $store_id;
        $stock->product_id = $product_id;
        $stock->stock_type = 'expired';
        $stock->previous_stock = $previous_stock;
        $stock->stock_in = 0;;
        $stock->stock_out = $qty;
        $stock->current_stock = $previous_stock - $qty;
        $stock->save();



        Toastr::success('Product Expired Created Successfully', 'Success');
        return redirect()->route('productExpireds.index');
    }




}

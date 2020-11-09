<?php

namespace App\Http\Controllers;

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
        //
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
            ->groupBy('product_purchase_details.product_id','product_purchase_details.id','product_purchase_details.expired_date')
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
        //$productPurchase = ProductPurchase::find($request->product_purchase_id);
        $productPurchaseDetail = ProductPurchaseDetail::find($request->product_purchase_detail_id);
        //dd($productPurchaseDetail);

        $product_expired = new ProductExpired();
        $product_expired->user_id = Auth::id();
        $product_expired->store_id = 1;
        $product_expired->product_purchase_id = $productPurchaseDetail->product_purchase_id;
        $product_expired->total_amount = $request->total_amount;
        $product_expired->save();

        $insert_id = $product_expired->id;
        if($insert_id)
        {
            $product_sale_return_detail = new ProductSaleReturnDetail();
            $product_sale_return_detail->product_expired_id = $insert_id;
            $product_sale_return_detail->product_purchase_detail_id = $insert_id;
            $product_sale_return_detail->product_category_id = $productSaleDetail->product_category_id;
            $product_sale_return_detail->product_sub_category_id = $productSaleDetail->product_sub_category_id;
            $product_sale_return_detail->product_brand_id = $productSaleDetail->product_brand_id;
            $product_sale_return_detail->product_id = $productSaleDetail->product_id;
            $product_sale_return_detail->qty = $request->return_qty;
            $product_sale_return_detail->price = $request->total_amount;
            $product_sale_return_detail->sub_total = $request->sub_total;
            $product_sale_return_detail->barcode = $request->barcode;
            $product_sale_return_detail->save();

            // transaction
            $transaction = new Transaction();
            //$transaction->invoice_no = 'return-'.$productSale->invoice_no;
            $transaction->user_id = Auth::id();
            $transaction->store_id = $productSale->store_id;
            $transaction->party_id = $productSale->party_id;
            $transaction->ref_id = $insert_id;
            $transaction->transaction_type = 'sale return';
            $transaction->payment_type = $request->payment_type;
            $transaction->amount = $request->total_amount;
            $transaction->save();

            $product_id = $productSaleDetail->product_id;


            $check_previous_stock = Stock::where('product_id',$product_id)->pluck('current_stock')->first();
            if(!empty($check_previous_stock)){
                $previous_stock = $check_previous_stock;
            }else{
                $previous_stock = 0;
            }

            // product stock
            $stock = new Stock();
            $stock->user_id = Auth::id();
            $stock->ref_id = $insert_id;
            $stock->store_id = $productSale->store_id;
            $stock->product_id = $product_id;
            $stock->stock_type = 'sale return';
            $stock->previous_stock = $previous_stock;
            $stock->stock_in = $request->return_qty;;
            $stock->stock_out = 0;
            $stock->current_stock = $previous_stock + $request->return_qty;
            $stock->save();


        }

        Toastr::success('Product Sale Return Created Successfully', 'Success');
        return redirect()->route('productSaleReturns.index');
    }




}

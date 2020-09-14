<?php

namespace App\Http\Controllers;

use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Http\Request;
use App\ProductSale;
use App\ProductSaleDetail;
use App\ProductSaleReturn;
use App\ProductSaleReturnDetail;
use App\Transaction;
use App\Stock;
use Illuminate\Support\Facades\Auth;

class ProductSaleReturnController extends Controller
{
    function __construct()
    {
        $this->middleware('permission:product-sale-return-list|product-sale-return-create|product-sale-return-edit|product-sale-return-delete', ['only' => ['index','show','returnableSaleProduct','saleProductReturn']]);
        $this->middleware('permission:product-sale-return-create', ['only' => ['create','store']]);
        $this->middleware('permission:product-sale-return-edit', ['only' => ['edit','update']]);
        $this->middleware('permission:product-sale-return-delete', ['only' => ['destroy']]);
    }

    public function index()
    {
        $productSaleReturns = ProductSaleReturn::all();
        //dd($productSaleReturns);
        return view('backend.productSaleReturn.index',compact('productSaleReturns'));
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
        $productSaleReturn = ProductSaleReturn::find($id);
        $productSaleReturnDetails = ProductSaleReturnDetail::where('product_sale_return_id',$id)->get();

        return view('backend.productSaleReturn.show', compact('productSaleReturn','productSaleReturnDetails'));
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

    public function returnableSaleProduct(){
        $returnable_sale_products = ProductSaleDetail::where('return_type','returnable')->get();
        //dd($returnable_sale_products);
        return view('backend.productSaleReturn.returnable_sale_products',compact('returnable_sale_products'));
    }

    public function saleProductReturn(Request $request){
        //dd($request->all());
        $productSale = ProductSale::find($request->product_sale_id);
        $productSaleDetail = ProductSaleDetail::find($request->product_sale_detail_id);
        //dd($productSaleDetail);

        $product_sale_return = new ProductSaleReturn();
        $product_sale_return->invoice_no = 'return-'.$productSale->invoice_no;
        $product_sale_return->sale_invoice_no = $productSale->invoice_no;
        $product_sale_return->product_sale_id = $productSale->id;
        $product_sale_return->user_id = Auth::id();
        $product_sale_return->store_id = $productSale->store_id;
        $product_sale_return->party_id = $productSale->party_id;
        $product_sale_return->payment_type = $productSale->payment_type;
        $product_sale_return->discount_type = $productSale->discount_type;
        $product_sale_return->discount_amount = 0;
        $product_sale_return->total_amount = $request->total_amount;
        $product_sale_return->save();

        $insert_id = $product_sale_return->id;
        if($insert_id)
        {
            $product_sale_return_detail = new ProductSaleReturnDetail();
            $product_sale_return_detail->product_sale_return_id = $insert_id;
            $product_sale_return_detail->product_sale_detail_id = $productSaleDetail->id;
            $product_sale_return_detail->product_category_id = $productSaleDetail->product_category_id;
            $product_sale_return_detail->product_sub_category_id = $productSaleDetail->product_sub_category_id;
            $product_sale_return_detail->product_brand_id = $productSaleDetail->product_brand_id;
            $product_sale_return_detail->product_id = $productSaleDetail->product_id;
            $product_sale_return_detail->qty = $request->return_qty;
            $product_sale_return_detail->price = $request->total_amount;
            $product_sale_return_detail->reason = $request->reason;
            $product_sale_return_detail->save();

            // transaction
            $transaction = new Transaction();
            $transaction->invoice_no = 'return-'.$productSale->invoice_no;
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

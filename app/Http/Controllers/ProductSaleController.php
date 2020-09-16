<?php

namespace App\Http\Controllers;

use App\Due;
use App\Party;
use App\Product;
use App\ProductBrand;
use App\ProductCategory;
use App\ProductPurchaseDetail;
use App\ProductSale;
use App\ProductSaleDetail;
use App\ProductSubCategory;
use App\Stock;
use App\Transaction;
use Illuminate\Support\Facades\Auth;
use App\Store;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ProductSaleController extends Controller
{

    function __construct()
    {
        $this->middleware('permission:product-sale-list|product-sale-create|product-sale-edit|product-sale-delete', ['only' => ['index','show']]);
        $this->middleware('permission:product-sale-create', ['only' => ['create','store']]);
        $this->middleware('permission:product-sale-edit', ['only' => ['edit','update']]);
        $this->middleware('permission:product-sale-delete', ['only' => ['destroy']]);
    }

    public function index()
    {
        $auth_user_id = Auth::user()->id;
        $auth_user = Auth::user()->roles[0]->name;
        if($auth_user == "Admin"){
            $productSales = ProductSale::latest()->get();
        }else{
            $productSales = ProductSale::where('user_id',$auth_user_id)->get();
        }
        return view('backend.productSale.index',compact('productSales'));
    }


    public function create()
    {
        $auth_user_id = Auth::user()->id;
        $auth_user = Auth::user()->roles[0]->name;
        $parties = Party::where('type','customer')->get() ;
        if($auth_user == "Admin"){
            $stores = Store::all();
        }else{
            $stores = Store::where('user_id',$auth_user_id)->get();
        }
        $productCategories = ProductCategory::all();
        $productSubCategories = ProductSubCategory::all();
        $productBrands = ProductBrand::all();
        $products = Product::all();
        return view('backend.productSale.create',compact('parties','stores','products','productCategories','productSubCategories','productBrands'));
    }


    public function store(Request $request)
    {
        //dd($request->all());
        $this->validate($request, [
            'name'=> 'required',
            'phone'=> 'required',
            //'party_id'=> 'required',
            'store_id'=> 'required',
            'product_id'=> 'required',
            'qty'=> 'required',
            'price'=> 'required',

        ]);

        $row_count = count($request->product_id);
        $total_amount = 0;
        for($i=0; $i<$row_count;$i++)
        {
            $total_amount += $request->sub_total[$i];
        }
        $discount_type = $request->discount_type;
        if($discount_type == 'flat'){
            $total_amount -= $request->discount_amount;
        }else{
            $total_amount = ($total_amount*$request->discount_amount)/100;
        }

        $get_invoice_no = ProductSale::latest()->pluck('invoice_no')->first();
        //dd($get_invoice_no);
        if(!empty($get_invoice_no)){
            $invoice_no = $get_invoice_no+1;
        }else{
            $invoice_no = 1000;
        }
        //dd($invoice_no);

        $party = new Party();
        $party->type='Customer';
        $party->name=$request->name;
        $party->slug=Str::slug($request->name);
        $party->phone=$request->phone;
        $party->email=$request->email;
        $party->address=$request->address;
        $party->status=1;
        $party->save();
        $party_id = $party->id;


        // product purchase
        $productSale = new ProductSale();
        $productSale->invoice_no = $invoice_no;
        $productSale->user_id = Auth::id();
        $productSale->party_id = $party_id;
        $productSale->store_id = $request->store_id;
        $productSale->payment_type = $request->payment_type;
        $productSale->delivery_service = $request->delivery_service;
        $productSale->delivery_service_charge = $request->delivery_service_charge;
        $productSale->discount_type = $request->discount_type;
        $productSale->discount_amount = $request->discount_amount;
        $productSale->total_amount = $total_amount;
        $productSale->paid_amount = $request->paid_amount;
        $productSale->due_amount = $request->due_amount;
        $productSale->save();
        $insert_id = $productSale->id;
        if($insert_id)
        {
            for($i=0; $i<$row_count;$i++)
            {
                // product purchase detail
                $purchase_sale_detail = new ProductSaleDetail();
                $purchase_sale_detail->product_sale_id = $insert_id;
                $purchase_sale_detail->return_type = $request->return_type[$i];
                $purchase_sale_detail->product_category_id = $request->product_category_id[$i];
                $purchase_sale_detail->product_sub_category_id = $request->product_sub_category_id[$i] ? $request->product_sub_category_id[$i] : NULL;
                $purchase_sale_detail->product_brand_id = $request->product_brand_id[$i];
                $purchase_sale_detail->product_id = $request->product_id[$i];
                $purchase_sale_detail->qty = $request->qty[$i];
                $purchase_sale_detail->price = $request->price[$i];
                $purchase_sale_detail->sub_total = $request->qty[$i]*$request->price[$i];
                $purchase_sale_detail->save();

                $product_id = $request->product_id[$i];
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
                $stock->store_id = $request->store_id;
                $stock->product_id = $request->product_id[$i];
                $stock->stock_type = 'sale';
                $stock->previous_stock = $previous_stock;
                $stock->stock_in = 0;
                $stock->stock_out = $request->qty[$i];
                $stock->current_stock = $previous_stock - $request->qty[$i];
                $stock->save();
            }

            // due
            $due = new Due();
            $due->invoice_no = $invoice_no;
            $due->ref_id = $insert_id;
            $due->user_id = Auth::id();
            $due->store_id = $request->store_id;
            $due->party_id = $party_id;
            $due->payment_type = $request->payment_type;
            $due->total_amount = $total_amount;
            $due->paid_amount = $request->paid_amount;
            $due->due_amount = $request->due_amount;
            $due->save();

            // transaction
            $transaction = new Transaction();
            $transaction->invoice_no = $invoice_no;
            $transaction->user_id = Auth::id();
            $transaction->store_id = $request->store_id;
            $transaction->party_id = $party_id;
            $transaction->ref_id = $insert_id;
            $transaction->transaction_type = 'sale';
            $transaction->payment_type = $request->payment_type;
            $transaction->amount = $total_amount;
            $transaction->save();
        }

        Toastr::success('Product Sale Created Successfully', 'Success');
        return redirect()->route('productSales.index');

    }


    public function show($id)
    {
        $productSale = ProductSale::find($id);
        $productSaleDetails = ProductSaleDetail::where('product_sale_id',$id)->get();

        return view('backend.productSale.show', compact('productSale','productSaleDetails'));
    }


    public function edit($id)
    {
        $auth_user_id = Auth::user()->id;
        $auth_user = Auth::user()->roles[0]->name;
        if($auth_user == "Admin"){
            $stores = Store::all();
        }else{
            $stores = Store::where('user_id',$auth_user_id)->get();
        }
        $parties = Party::where('type','customer')->get() ;
        $products = Product::all();
        $productSale = ProductSale::find($id);
        $productCategories = ProductCategory::all();
        $productSubCategories = ProductSubCategory::all();
        $productBrands = ProductBrand::all();
        $productSaleDetails = ProductSaleDetail::where('product_sale_id',$id)->get();

        $party_id = $productSale->party_id;
        $party = Party::find($party_id);
        return view('backend.productSale.edit',compact('parties','stores','products','productSale','productSaleDetails','productCategories','productSubCategories','productBrands','party'));
    }


    public function update(Request $request, $id)
    {
        //dd($request->all());
        $this->validate($request, [
            'name'=> 'required',
            'phone'=> 'required',
            //'party_id'=> 'required',
            'store_id'=> 'required',
            'product_id'=> 'required',
            'qty'=> 'required',
            'price'=> 'required',

        ]);

        $row_count = count($request->product_id);
        $total_amount = 0;
        for($i=0; $i<$row_count;$i++)
        {
            $total_amount += $request->sub_total[$i];
        }

        $discount_type = $request->discount_type;
        if($discount_type == 'flat'){
            $total_amount -= $request->discount_amount;
        }else{
            $total_amount = ($total_amount*$request->discount_amount)/100;
        }

        $party_id=$request->party_id;
        $party = Party::find($party_id);
        $party->name=$request->name;
        $party->slug=Str::slug($request->name);
        $party->phone=$request->phone;
        $party->email=$request->email;
        $party->address=$request->address;
        $party->update();


        // product purchase
        $productSale = ProductSale::find($id);
        $productSale->user_id = Auth::id();
        $productSale->party_id = $request->party_id;
        $productSale->store_id = $request->store_id;
        $productSale->payment_type = $request->payment_type;
        $productSale->delivery_service = $request->delivery_service;
        $productSale->delivery_service_charge = $request->delivery_service_charge;
        $productSale->discount_type = $request->discount_type;
        $productSale->discount_amount = $request->discount_amount;
        $productSale->total_amount = $total_amount;
        $productSale->paid_amount = $request->paid_amount;
        $productSale->due_amount = $request->due_amount;
        $productSale->update();

        for($i=0; $i<$row_count;$i++)
        {
            // product purchase detail
            $product_sale_detail_id = $request->product_Sale_detail_id[$i];
            //dd($product_sale_detail_id);
            $purchase_sale_detail = ProductsaleDetail::findOrFail($product_sale_detail_id);
            //dd($purchase_sale_detail);
            $purchase_sale_detail->return_type = $request->return_type[$i];
            $purchase_sale_detail->product_category_id = $request->product_category_id[$i];
            $purchase_sale_detail->product_sub_category_id = $request->product_sub_category_id[$i] ? $request->product_sub_category_id[$i] : NULL;
            $purchase_sale_detail->product_brand_id = $request->product_brand_id[$i];
            $purchase_sale_detail->product_id = $request->product_id[$i];
            $purchase_sale_detail->qty = $request->qty[$i];
            $purchase_sale_detail->price = $request->price[$i];
            $purchase_sale_detail->sub_total = $request->qty[$i]*$request->price[$i];
            $purchase_sale_detail->update();


            $product_id = $request->product_id[$i];
            $check_previous_stock = Stock::where('product_id',$product_id)->latest()->pluck('current_stock')->first();
            if(!empty($check_previous_stock)){
                $previous_stock = $check_previous_stock;
            }else{
                $previous_stock = 0;
            }
            // product stock
            $stock = Stock::where('ref_id',$id)->where('stock_type','sale')->first();
            $stock->user_id = Auth::id();
            $stock->store_id = $request->store_id;
            $stock->product_id = $request->product_id[$i];
            $stock->previous_stock = $previous_stock;
            $stock->stock_in = 0;
            $stock->stock_out = $request->qty[$i];
            $stock->current_stock = $previous_stock - $request->qty[$i];
            $stock->update();
        }

        // due
        $due = Due::where('ref_id',$id)->first();;
        $due->user_id = Auth::id();
        $due->store_id = $request->store_id;
        $due->party_id = $request->party_id;
        $due->payment_type = $request->payment_type;
        $due->total_amount = $total_amount;
        $due->paid_amount = $request->paid_amount;
        $due->due_amount = $request->due_amount;
        $due->update();

        // transaction
        $transaction = Transaction::where('ref_id',$id)->where('transaction_type','sale')->first();
        $transaction->user_id = Auth::id();
        $transaction->store_id = $request->store_id;
        $transaction->party_id = $request->party_id;
        $transaction->payment_type = $request->payment_type;
        $transaction->amount = $total_amount;
        $transaction->update();

        Toastr::success('Product Sale Updated Successfully', 'Success');
        return redirect()->route('productSales.index');
    }


    public function destroy($id)
    {
        $productSale = ProductSale::find($id);
        $productSale->delete();

        DB::table('product_sale_details')->where('product_sale_id',$id)->delete();
        DB::table('stocks')->where('ref_id',$id)->delete();
        DB::table('transactions')->where('ref_id',$id)->delete();

        Toastr::success('Product Sale Deleted Successfully', 'Success');
        return redirect()->route('productSales.index');
    }

    public function productSaleRelationData(Request $request){
        $store_id = $request->store_id;
        $product_id = $request->current_product_id;
        $current_stock = Stock::where('store_id',$store_id)->where('product_id',$product_id)->latest()->pluck('current_stock')->first();
        $mrp_price = ProductPurchaseDetail::join('product_purchases', 'product_purchase_details.product_purchase_id', '=', 'product_purchases.id')
            ->where('store_id',$store_id)->where('product_id',$product_id)
            ->latest('product_purchase_details.id')
            ->pluck('product_purchase_details.mrp_price')
            ->first();

        $product_category_id = Product::where('id',$product_id)->pluck('product_category_id')->first();
        $product_sub_category_id = Product::where('id',$product_id)->pluck('product_sub_category_id')->first();
        $product_brand_id = Product::where('id',$product_id)->pluck('product_brand_id')->first();
        $options = [
            'mrp_price' => $mrp_price,
            'current_stock' => $current_stock,
            'categoryOptions' => '',
            'subCategoryOptions' => '',
            'brandOptions' => '',
        ];

        if($product_category_id){
            $categories = ProductCategory::where('id',$product_category_id)->get();
            if(count($categories) > 0){
                $options['categoryOptions'] = "<select class='form-control' name='product_category_id[]' readonly>";
                foreach($categories as $category){
                    $options['categoryOptions'] .= "<option value='$category->id'>$category->name</option>";
                }
                $options['categoryOptions'] .= "</select>";
            }
        }else{
            $options['categoryOptions'] = "<select class='form-control' name='product_sub_category_id[]' readonly>";
            $options['categoryOptions'] .= "<option value=''>No Data Found!</option>";
            $options['categoryOptions'] .= "</select>";
        }
        if(!empty($product_sub_category_id)){
            $subCategories = ProductSubCategory::where('id',$product_sub_category_id)->get();
            if(count($subCategories) > 0){
                $options['subCategoryOptions'] = "<select class='form-control' name='product_sub_category_id[]' readonly>";
                foreach($subCategories as $subCategory){
                    $options['subCategoryOptions'] .= "<option value='$subCategory->id'>$subCategory->name</option>";
                }
                $options['subCategoryOptions'] .= "</select>";
            }
        }else{
            $options['subCategoryOptions'] = "<select class='form-control' name='product_sub_category_id[]' readonly>";
            $options['subCategoryOptions'] .= "<option value=''>No Data Found!</option>";
            $options['subCategoryOptions'] .= "</select>";
        }
        if($product_brand_id){
            $brands = ProductBrand::where('id',$product_brand_id)->get();
            if(count($brands) > 0){
                $options['brandOptions'] = "<select class='form-control' name='product_brand_id[]'readonly>";
                foreach($brands as $brand){
                    $options['brandOptions'] .= "<option value='$brand->id'>$brand->name</option>";
                }
                $options['brandOptions'] .= "</select>";
            }
        }else{
            $options['brandOptions'] = "<select class='form-control' name='product_sub_category_id[]' readonly>";
            $options['brandOptions'] .= "<option value=''>No Data Found!</option>";
            $options['brandOptions'] .= "</select>";
        }

        return response()->json(['success'=>true,'data'=>$options]);
    }

    public function invoice($id)
    {
        $productSale = ProductSale::find($id);
        $productSaleDetails = ProductSaleDetail::where('product_sale_id',$id)->get();
        $transaction = Transaction::where('ref_id',$id)->get();
        $store_id = $productSale->store_id;
        $party_id = $productSale->party_id;
        $store = Store::find($store_id);
        $party = Party::find($party_id);

        return view('backend.productSale.invoice', compact('productSale','productSaleDetails','transaction','store','party'));
    }
    public function invoicePrint($id)
    {
        $productSale = ProductSale::find($id);
        $productSaleDetails = ProductSaleDetail::where('product_sale_id',$id)->get();
        $transaction = Transaction::where('ref_id',$id)->get();
        $store_id = $productSale->store_id;
        $party_id = $productSale->party_id;
        $store = Store::find($store_id);
        $party = Party::find($party_id);

        return view('backend.productSale.invoice-print', compact('productSale','productSaleDetails','transaction','store','party'));return view('backend.productSale.invoice-print');
    }

    public function newParty(Request $request){
        //dd($request->all());
        $this->validate($request, [
            'type'=> 'required',
            'name' => 'required',
            'phone'=> 'required',
            'email'=> '',
            'address'=> '',
        ]);
        $parties = new Party();
        $parties->type = $request->type;
        $parties->name = $request->name;
        $parties->slug = Str::slug($request->name);
        $parties->phone = $request->phone;
        $parties->email = $request->email;
        $parties->address = $request->address;
        $parties->status = 1;
        $parties->save();
        $insert_id = $parties->id;

        if ($insert_id){
            $sdata['id'] = $insert_id;
            $sdata['name'] = $parties->name;
            echo json_encode($sdata);

        }
        else {
            $data['exception'] = 'Some thing mistake !';
            echo json_encode($data);

        }
    }

    public function payDue(Request $request){
        //dd($request->all());
        $product_sale_id = $request->product_sale_id;
        $product_sale = ProductSale::find($product_sale_id);

        $total_amount=$product_sale->total_amount;
        $paid_amount=$product_sale->paid_amount;

        $product_sale->paid_amount=$paid_amount+$request->new_paid;
        $product_sale->due_amount=$total_amount-($paid_amount+$request->new_paid);
        $product_sale->update();

        $due = new Due();
        $due->invoice_no=$product_sale->invoice_no;
        $due->ref_id=$request->product_sale_id;
        $due->user_id=$product_sale->user_id;
        $due->store_id=$product_sale->store_id;
        $due->party_id=$product_sale->party_id;
        $due->payment_type=$product_sale->payment_type;
        $due->total_amount=$product_sale->total_amount;
        $due->paid_amount=$request->new_paid;
        $due->due_amount=$total_amount-($paid_amount+$request->new_paid);
        $due->save();

        Toastr::success('Due Pay Successfully', 'Success');
        return redirect()->back();

    }
}

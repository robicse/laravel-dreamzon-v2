<?php

namespace App\Http\Controllers;

use App\Party;
use App\Product;
use App\ProductBrand;
use App\ProductCategory;
use App\ProductPurchase;
use App\ProductPurchaseDetail;
use App\ProductSubCategory;
use App\PurchaseOrder;
use App\Stock;
use App\Transaction;
use Illuminate\Support\Facades\Auth;
use App\Store;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Str;


class ProductPurchaseController extends Controller
{

    function __construct()
    {
        $this->middleware('permission:product-purchase-list|product-purchase-create|product-purchase-edit|product-purchase-delete', ['only' => ['index','show']]);
        $this->middleware('permission:product-purchase-create', ['only' => ['create','store']]);
        $this->middleware('permission:product-purchase-edit', ['only' => ['edit','update']]);
        $this->middleware('permission:product-purchase-delete', ['only' => ['destroy']]);
    }

    public function index()
    {
        $invoice_no = Input::get('invoice_no') ? Input::get('invoice_no') : '';
        $party_id = Input::get('party_id') ? Input::get('party_id') : '';
        $product_id = Input::get('product_id') ? Input::get('product_id') : '';
        if($invoice_no && $party_id && $product_id)
        {
            $product_purchase_id = ProductPurchaseDetail::where('product_id',$product_id)->latest()->pluck('product_purchase_id')->first();
            $productPurchases = ProductPurchase::where('id',$product_purchase_id)->where('invoice_no',$invoice_no)->where('party_id',$party_id)->latest()->get();
        }
        else if($invoice_no && $party_id)
        {
            $productPurchases = ProductPurchase::where('invoice_no',$invoice_no)->where('party_id',$party_id)->latest()->get();
        }
        else if($invoice_no && $product_id)
        {
            $product_purchase_id = ProductPurchaseDetail::where('product_id',$product_id)->latest()->pluck('product_purchase_id')->first();
            $productPurchases = ProductPurchase::where('id',$product_purchase_id)->where('invoice_no',$invoice_no)->latest()->get();
        }
        else if($party_id && $product_id)
        {
            $product_purchase_id = ProductPurchaseDetail::where('product_id',$product_id)->latest()->pluck('product_purchase_id')->first();
            $productPurchases = ProductPurchase::where('id',$product_purchase_id)->where('party_id',$party_id)->latest()->get();
        }
        else if($invoice_no)
        {
            $productPurchases = ProductPurchase::where('invoice_no',$invoice_no)->latest()->get();
        }
        else if($party_id)
        {
            $productPurchases = ProductPurchase::where('party_id',$party_id)->latest()->get();
        }
        else if($product_id)
        {
            $product_purchase_id = ProductPurchaseDetail::where('product_id',$product_id)->latest()->pluck('product_purchase_id')->first();
            $productPurchases = ProductPurchase::where('id',$product_purchase_id)->latest()->get();
        }else{
            $productPurchases = ProductPurchase::latest()->get();
        }

        $parties = Party::all();
        $products = Product::all();

        return view('backend.productPurchase.index',compact('productPurchases','parties','products','invoice_no','party_id','product_id'));
    }

    function product_store_stock_sync($product_id,$store_id){

        $stock_data = Stock::where('product_id',$product_id)->where('store_id',$store_id)->get();
        $row_count = count($stock_data);
        if($row_count > 0){
            $store_previous_row_current_stock = null;
            $stock_in_flag = 0;
            $stock_out_flag = 0;

            foreach ($stock_data as $key => $data){

                $id = $data->id;
                $previous_stock = $data->previous_stock;
                $stock_in = $data->stock_in;
                $stock_out = $data->stock_out;
                $current_stock = $data->current_stock;



                if($key == 0){
//                    echo 'row_id =>'.$id.'<br/>';
//                    echo 'product_id =>'.$product_id.'<br/>';
//                    echo 'store_id =>'.$store_id.'<br/>';
//
//                    echo 'store_previous_row_current_stock '.$store_previous_row_current_stock.'<br/>';
//                    echo 'this_row_current_stock =>'.$current_stock.'<br/>';
//                    echo '<br/>';

                    $stock = Stock::find($id);
                    $stock->previous_stock = 0;
                    $stock->current_stock = $stock_in;
                    $affectedRow = $stock->update();
                    if($affectedRow){
//                        echo 'this_row_current_stock => updated => '.$stock_in.'<br/>';
//                        echo '<br/>';
                        $current_stock = $stock->current_stock;
                    }

                }else{
//                    echo 'row_id =>'.$id.'<br/>';
//                    echo 'product_id =>'.$product_id.'<br/>';
//                    echo 'store_id =>'.$store_id.'<br/>';
//
//                    echo 'store_previous_row_current_stock '.$store_previous_row_current_stock.'<br/>';
//                    echo 'this_row_current_stock =>'.$current_stock.'<br/>';
//                    echo '<br/>';

                    // update part
                    if($stock_in > 0){
                        if($stock_in_flag == 1){
                            $stock = Stock::find($id);
                            $stock->previous_stock = $store_previous_row_current_stock;
                            $stock->current_stock = $store_previous_row_current_stock + $stock_in;
                            $affectedRow = $stock->update();
                            if($affectedRow){
//                                echo 'this_row_current_stock => updated => '.$stock_in.'<br/>';
//                                echo '<br/>';
                                $current_stock = $stock->current_stock;
                            }
                        }else if($previous_stock != $store_previous_row_current_stock){
                            $stock_in_flag = 1;

                            $stock = Stock::find($id);
                            $stock->previous_stock = $store_previous_row_current_stock;
                            $stock->current_stock = $store_previous_row_current_stock + $stock_in;
                            $affectedRow = $stock->update();
                            if($affectedRow){
//                                echo 'this_row_current_stock => updated => '.$stock_in.'<br/>';
//                                echo '<br/>';
                                $current_stock = $stock->current_stock;
                            }
                        }else{
//                            echo 'this_row_current_stock => nothing => '.$stock_in.'<br/>';
//                            echo '<br/>';
                        }
                    }else if($stock_out > 0){
                        if($stock_out_flag == 1) {
                            $stock = Stock::find($id);
                            $stock->previous_stock = $store_previous_row_current_stock;
                            $stock->current_stock = $store_previous_row_current_stock - $stock_out;
                            $affectedRow = $stock->update();
                            if ($affectedRow) {
//                                echo 'This Row('.$id.') Current Stock => updated => ' . $stock_out . '<br/>';
//                                echo '<br/>';
                                $current_stock = $stock->current_stock;
                            }
                        }else if($previous_stock != $store_previous_row_current_stock) {
                            $stock_out_flag = 1;

                            $stock = Stock::find($id);
                            $stock->previous_stock = $store_previous_row_current_stock;
                            $stock->current_stock = $store_previous_row_current_stock - $stock_out;
                            $affectedRow = $stock->update();
                            if ($affectedRow) {
//                                echo 'This Row('.$id.') Current Stock => updated =>' . $stock_out . '<br/>';
//                                echo '<br/>';
                                $current_stock = $stock->current_stock;
                            }
                        }else{
//                            echo 'this_row_current_stock => nothing => '.$stock_out.'<br/>';
//                            echo '<br/>';
                        }
                    }else{
//                        echo 'this_row_current_stock => nothing<br/>';
//                        echo '<br/>';
                    }
//                    echo '<br/>';
                }
                $store_previous_row_current_stock = $current_stock;
            }
        }else{
//            echo 'no found!'.'<br/>';
        }
    }


    function stock_sync(){
        $stock_data = Stock::whereIn('id', function($query) {
            $query->from('stocks')->groupBy('store_id')->groupBy('product_id')->selectRaw('MIN(id)');
        })->get();

        $row_count = count($stock_data);
        if($row_count > 0){
            foreach ($stock_data as $key => $data){
                $product_id = $data->product_id;
                $store_id = $data->store_id;
                $this->product_store_stock_sync($product_id,$store_id);
            }
            //Toastr::success('Stock Synchronize Successfully Updated!', 'Success');
        }
        return redirect()->back();
    }


    public function create()
    {
        // stock sync
        $this->stock_sync();


        $parties = Party::where('type','supplier')->get() ;
        $auth_user_id = Auth::user()->id;
        $auth_user = Auth::user()->roles[0]->name;
        if($auth_user == "Admin"){
            $stores = Store::all();
        }else{
            $stores = Store::where('user_id',$auth_user_id)->get();
        }
        $productCategories = ProductCategory::all();
        $productSubCategories = ProductSubCategory::all();
        $productBrands = ProductBrand::all();
        $products = Product::all();
        return view('backend.productPurchase.create',compact('parties','stores','products','productCategories','productSubCategories','productBrands'));
    }


    public function store(Request $request)
    {
        //dd($request->all());
        $this->validate($request, [
            'party_id'=> 'required',
            'store_id'=> 'required',
            'product_id'=> 'required',
            'qty'=> 'required',
            'price'=> 'required',
            'mrp_price'=> 'required',
        ]);

        $row_count = count($request->product_id);
        $total_amount = 0;
        for($i=0; $i<$row_count;$i++)
        {
            $total_amount += $request->sub_total[$i];
        }

        $get_invoice_no = ProductPurchase::latest()->pluck('invoice_no')->first();
        //dd($get_invoice_no);
        if(!empty($get_invoice_no)){
            $get_invoice = str_replace("purchase-","",$get_invoice_no);
            //$invoice_no = $get_invoice_no+1;
            $invoice_no = $get_invoice+1;
        }else{
            $invoice_no = 1000;
        }
        //dd($invoice_no);

        // product purchase
        $productPurchase = new ProductPurchase();
        $productPurchase ->invoice_no = 'purchase-'.$invoice_no;
        $productPurchase ->party_id = $request->party_id;
        $productPurchase ->store_id = $request->store_id;
        $productPurchase ->user_id = Auth::id();
        $productPurchase ->payment_type = $request->payment_type;
        $productPurchase ->total_amount = $total_amount;
        $productPurchase->save();
        $insert_id = $productPurchase->id;
        if($insert_id)
        {
            for($i=0; $i<$row_count;$i++)
            {
                $product_id = $request->product_id[$i];
                $barcode = Product::where('id',$product_id)->pluck('barcode')->first();

                // product purchase detail
                $purchase_purchase_detail = new ProductPurchaseDetail();
                $purchase_purchase_detail->product_purchase_id = $insert_id;
                $purchase_purchase_detail->product_category_id = $request->product_category_id[$i];
                $purchase_purchase_detail->product_sub_category_id = $request->product_sub_category_id[$i] ? $request->product_sub_category_id[$i] : NULL;
                $purchase_purchase_detail->product_brand_id = $request->product_brand_id[$i];
                $purchase_purchase_detail->product_id = $request->product_id[$i];
                $purchase_purchase_detail->qty = $request->qty[$i];
                $purchase_purchase_detail->price = $request->price[$i];
                $purchase_purchase_detail->mrp_price = $request->mrp_price[$i];
                $purchase_purchase_detail->sub_total = $request->qty[$i]*$request->price[$i];
                $purchase_purchase_detail->barcode = $barcode;
                $purchase_purchase_detail->expired_date = $request->expired_date[$i];
                $purchase_purchase_detail->save();

                $check_previous_stock = Stock::where('store_id',$request->store_id)->where('product_id',$product_id)->latest()->pluck('current_stock')->first();
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
                $stock->stock_type = 'purchase';
                $stock->previous_stock = $previous_stock;
                $stock->stock_in = $request->qty[$i];
                $stock->stock_out = 0;
                $stock->current_stock = $previous_stock + $request->qty[$i];
                $stock->date = date('Y-m-d');
                $stock->save();
            }

            // transaction
            $transaction = new Transaction();
            $transaction->invoice_no = 'purchase-'.$invoice_no;
            $transaction->user_id = Auth::id();
            $transaction->store_id = $request->store_id;
            $transaction->party_id = $request->party_id;
            $transaction->ref_id = $insert_id;
            $transaction->transaction_type = 'purchase';
            $transaction->payment_type = $request->payment_type;
            $transaction->amount = $total_amount;
            $transaction->date = date('Y-m-d');
            $transaction->save();
        }

        Toastr::success('Product Purchase Created Successfully', 'Success');
        return redirect()->route('productPurchases.index');

    }


    public function show($id)
    {
        $productPurchase = ProductPurchase::find($id);
        $productPurchaseDetails = ProductPurchaseDetail::where('product_purchase_id',$id)->get();

        return view('backend.productPurchase.show', compact('productPurchase','productPurchaseDetails'));
    }


    public function edit($id)
    {
        $parties = Party::where('type','supplier')->get() ;
        $auth_user_id = Auth::user()->id;
        $auth_user = Auth::user()->roles[0]->name;
        if($auth_user == "Admin"){
            $stores = Store::all();
        }else{
            $stores = Store::where('user_id',$auth_user_id)->get();
        }
        $products = Product::all();
        $productPurchase = ProductPurchase::find($id);
        $productCategories = ProductCategory::all();
        $productSubCategories = ProductSubCategory::all();
        $productBrands = ProductBrand::all();
        $productPurchaseDetails = ProductPurchaseDetail::where('product_purchase_id',$id)->get();
        return view('backend.productPurchase.edit',compact('parties','stores','products','productPurchase','productPurchaseDetails','productCategories','productSubCategories','productBrands'));
    }


    public function update(Request $request, $id)
    {
        //dd($request->all());
        $this->validate($request, [
            'party_id'=> 'required',
            'store_id'=> 'required',
            'product_id'=> 'required',
            'qty'=> 'required',
            'price'=> 'required',
            'mrp_price'=> 'required',
        ]);

        $row_count = count($request->product_id);
        $total_amount = 0;
        for($i=0; $i<$row_count;$i++)
        {
            $total_amount += $request->sub_total[$i];
        }

        // product purchase
        $productPurchase = ProductPurchase::find($id);
        $productPurchase ->party_id = $request->party_id;
        $productPurchase ->store_id = $request->store_id;
        $productPurchase ->user_id = Auth::id();
        $productPurchase ->payment_type = $request->payment_type;
        $productPurchase ->total_amount = $total_amount;
        $affectedRows = $productPurchase->update();

        if($affectedRows){
            for($i=0; $i<$row_count;$i++)
            {
                $product_id = $request->product_id[$i];
                $barcode = Product::where('id',$product_id)->pluck('barcode')->first();

                // product purchase detail
                $product_purchase_detail_id = $request->product_purchase_detail_id[$i];
                $purchase_purchase_detail = ProductPurchaseDetail::findOrFail($product_purchase_detail_id);;

                // get purchase_current_stock
                $purchase_current_qty = $purchase_purchase_detail->qty;

                $purchase_purchase_detail->product_category_id = $request->product_category_id[$i];
                $purchase_purchase_detail->product_sub_category_id = $request->product_sub_category_id[$i] ? $request->product_sub_category_id[$i] : NULL;
                $purchase_purchase_detail->product_brand_id = $request->product_brand_id[$i];
                $purchase_purchase_detail->product_id = $request->product_id[$i];
                $purchase_purchase_detail->qty = $request->qty[$i];
                $purchase_purchase_detail->price = $request->price[$i];
                $purchase_purchase_detail->mrp_price = $request->mrp_price[$i];
                $purchase_purchase_detail->sub_total = $request->qty[$i]*$request->price[$i];
                $purchase_purchase_detail->barcode = $barcode;
                $purchase_purchase_detail->expired_date = $request->expired_date[$i];
                $purchase_purchase_detail->update();


                // product stock
                //$stock_row = Stock::where('ref_id',$id)->where('store_id',$request->store_id)->where('stock_type','purchase')->where('product_id',$product_id)->latest()->first();
                //$purchase_current_stock = $stock_row->current_stock;
                $previous_current_stock = Stock::where('store_id',$request->store_id)->where('product_id',$product_id)->latest()->pluck('current_stock')->first();



                if($purchase_current_qty != $request->qty[$i]){

//                    if($request->qty[$i] > $stock_row->stock_in){
//                        $add_or_minus_stock_in = $request->qty[$i] - $stock_row->stock_in;
//                        $update_stock_in = $stock_row->stock_in + $add_or_minus_stock_in;
//                        $update_current_stock = $stock_row->current_stock + $add_or_minus_stock_in;
//                    }else{
//                        $add_or_minus_stock_in =  $stock_row->stock_in - $request->qty[$i];
//                        $update_stock_in = $stock_row->stock_in - $add_or_minus_stock_in;
//                        $update_current_stock = $stock_row->current_stock - $add_or_minus_stock_in;
//                    }
//
//
//                    //$stock_in = $stock->stock_in;
//                    $stock_row->user_id = Auth::user()->id;
//                    //$stock->store_id = $request->store_id;
//                    //$stock->product_id = $request->product_id[$i];
//                    //$stock->previous_stock = $previous_stock;
//                    $stock_row->stock_in = $update_stock_in;
//                    //$stock->stock_out = 0;
//                    $stock_row->current_stock = $update_current_stock;
//                    $stock_row->update();



                    if($request->qty[$i] > $purchase_current_qty){
                        $new_qty = $request->qty[$i] - $purchase_current_qty;

                        // product stock increase
                        $stock = new Stock();
                        $stock->user_id = Auth::id();
                        $stock->ref_id = $productPurchase->id;
                        $stock->store_id = $request->store_id;
                        $stock->product_id = $request->product_id[$i];
                        $stock->stock_type = 'purchase increase';
                        $stock->previous_stock = $previous_current_stock;
                        $stock->stock_in = $new_qty;
                        $stock->stock_out = 0;
                        $stock->current_stock = $previous_current_stock + $new_qty;
                        $stock->date = date('Y-m-d');
                        $stock->save();
                    }else{
                        $new_qty = $purchase_current_qty - $request->qty[$i];

                        // product stock decrease
                        $stock = new Stock();
                        $stock->user_id = Auth::id();
                        $stock->ref_id = $productPurchase->id;
                        $stock->store_id = $request->store_id;
                        $stock->product_id = $request->product_id[$i];
                        $stock->stock_type = 'purchase decrease';
                        $stock->previous_stock = $previous_current_stock;
                        $stock->stock_in = 0;
                        $stock->stock_out = $new_qty;
                        //dd($stock);
                        $stock->current_stock = $previous_current_stock - ($purchase_current_qty - $request->qty[$i]);
                        $stock->date = date('Y-m-d');
                        $stock->save();
                    }

                }
            }

            // transaction
            $transaction = Transaction::where('ref_id',$id)->where('transaction_type','purchase')->first();
            //$transaction->invoice_no = Null;
            $transaction->user_id = Auth::id();
            $transaction->store_id = $request->store_id;
            $transaction->party_id = $request->party_id;
            $transaction->payment_type = $request->payment_type;
            $transaction->amount = $total_amount;
            $transaction->update();
        }

        Toastr::success('Product Purchases Updated Successfully', 'Success');
        return redirect()->route('productPurchases.index');
    }


    public function destroy($id)
    {
        $productPurchase = ProductPurchase::find($id);
        $productPurchase->delete();

        DB::table('product_purchase_details')->where('product_purchase_id',$id)->delete();
        DB::table('stocks')->where('ref_id',$id)->delete();
        DB::table('transactions')->where('ref_id',$id)->delete();

        Toastr::success('Product purchase Deleted Successfully', 'Success');
        return redirect()->route('productPurchases.index');
    }

    public function productRelationData(Request $request){
        $product_id = $request->current_product_id;
        $product_category_id = Product::where('id',$product_id)->pluck('product_category_id')->first();
        $product_sub_category_id = Product::where('id',$product_id)->pluck('product_sub_category_id')->first();
        $product_brand_id = Product::where('id',$product_id)->pluck('product_brand_id')->first();
        $options = [
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
    public function invoice()
    {
        return view('backend.productPurchase.invoice');
    }
    public function invoicePrint()
    {
        return view('backend.productPurchase.invoice-print');
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

    public function createPOS()
    {

    }


    public function storePOS(Request $request)
    {
        //dd($request->all());
        $this->validate($request, [
            'party_id'=> 'required',
            'store_id'=> 'required',
            'product_id'=> 'required',
            'qty'=> 'required',
            'price'=> 'required',
            'mrp_price'=> 'required',
        ]);

        $row_count = count($request->product_id);
        $total_amount = 0;
        for($i=0; $i<$row_count;$i++)
        {
            $total_amount += $request->sub_total[$i];
        }

        // product purchase
        $productPurchase = new ProductPurchase();
        $productPurchase ->party_id = $request->party_id;
        $productPurchase ->store_id = $request->store_id;
        $productPurchase ->user_id = Auth::id();
        $productPurchase ->payment_type = $request->payment_type;
        $productPurchase ->total_amount = $total_amount;
        $productPurchase->save();
        $insert_id = $productPurchase->id;
        if($insert_id)
        {
            for($i=0; $i<$row_count;$i++)
            {
                $product_id = $request->product_id[$i];
                $barcode = Product::where('id',$product_id)->pluck('barcode')->first();

                // product purchase detail
                $purchase_purchase_detail = new ProductPurchaseDetail();
                $purchase_purchase_detail->product_purchase_id = $insert_id;
                $purchase_purchase_detail->product_category_id = $request->product_category_id[$i];
                $purchase_purchase_detail->product_sub_category_id = $request->product_sub_category_id[$i] ? $request->product_sub_category_id[$i] : NULL;
                $purchase_purchase_detail->product_brand_id = $request->product_brand_id[$i];
                $purchase_purchase_detail->product_id = $request->product_id[$i];
                $purchase_purchase_detail->qty = $request->qty[$i];
                $purchase_purchase_detail->price = $request->price[$i];
                $purchase_purchase_detail->mrp_price = $request->mrp_price[$i];
                $purchase_purchase_detail->sub_total = $request->qty[$i]*$request->price[$i];
                $purchase_purchase_detail->barcode = $barcode;
                $purchase_purchase_detail->save();

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
                $stock->store_id = $request->store_id;
                $stock->product_id = $request->product_id[$i];
                $stock->stock_type = 'purchase';
                $stock->previous_stock = $previous_stock;
                $stock->stock_in = $request->qty[$i];
                $stock->stock_out = 0;
                $stock->current_stock = $previous_stock + $request->qty[$i];
                $stock->save();
            }

            // transaction
            $transaction = new Transaction();
            $transaction->invoice_no = Null;
            $transaction->user_id = Auth::id();
            $transaction->store_id = $request->store_id;
            $transaction->party_id = $request->party_id;
            $transaction->ref_id = $insert_id;
            $transaction->transaction_type = 'purchase';
            $transaction->payment_type = $request->payment_type;
            $transaction->amount = $total_amount;
            $transaction->save();
        }

        Toastr::success('Product Purchase Created Successfully', 'Success');
        return redirect()->route('productPurchases.index');

    }
}

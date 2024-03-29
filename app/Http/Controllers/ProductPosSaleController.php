<?php

namespace App\Http\Controllers;

use App\Due;
use App\Party;
use App\Product;
use App\ProductBrand;
use App\ProductCategory;
use App\ProductSale;
use App\ProductSaleDetail;
use App\ProductSubCategory;
use App\Stock;
use App\Store;
use App\Transaction;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Gloudemans\Shoppingcart\Facades\Cart;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;

class ProductPosSaleController extends Controller
{
    public function index()
    {
        // manually product sale discount add
//        $productPosSales = ProductSale::where('sale_type','pos')->get();
//        if(count($productPosSales)){
//            foreach ($productPosSales as $productPosSale){
//                //echo $productPosSale->id.'<br/>';
//                if($productPosSale->discount_amount > 0){
//                    //echo $productPosSale->id.'<br/>';
//                    $discount_amount = $productPosSale->discount_amount.'<br/>';
//                    $total_amount = $productPosSale->total_amount.'<br/>';
//                    //echo '<br/>';
//
//                    $productSaleDetails = ProductSaleDetail::where('product_sale_id',$productPosSale->id)->get();
//                    foreach ($productSaleDetails as $productSaleDetail){
//
//                        //echo $discount_amount;
//                        //echo $total_amount;
//                        $price = $productSaleDetail->price;
//                        $final_discount_amount = (float)$discount_amount * (float)$price;
//                        $final_total_amount = (float)$discount_amount + (float)$total_amount;
//                        //echo '<br/>';
//                        //echo '<br/>';
//
//
//
//                        $discount = (float)$final_discount_amount/(float)$final_total_amount;
//                        $discount = round($discount);
//
//                        $updateProductSaleDetailDiscount = ProductSaleDetail::find($productSaleDetail->id);
//                        $updateProductSaleDetailDiscount->discount=$discount;
//                        $affectedRow = $updateProductSaleDetailDiscount->update();
//                        if($affectedRow){
//                            echo 'updated id = '.$productSaleDetail->id;
//                            echo 'updated discount = '.$discount;
//                            echo '<br/>';
//                        }
//                    }
//                }
//            }
//        }
//        dd('ops');





        //Session::put('product_sale_id',14);
        Session::forget('product_sale_id');

        $auth = Auth::user();
        $auth_user = Auth::user()->roles[0]->name;
        if($auth_user == "Admin"){
            $productPosSales = ProductSale::where('sale_type','pos')->latest()->get();
        }else{
            $productPosSales = ProductSale::where('sale_type','pos')->where('user_id',$auth->store_id)->latest()->get();
        }
        return view('backend.productPosSale.index',compact('productPosSales'));
    }

    public function showProductByStore(Request $request){
        $store_id = $request->store_id;
//        $products = DB::table('product_purchase_details')
//            ->select('product_purchase_details.product_id','products.barcode')
//            ->join('product_purchases','product_purchases.id','=','product_purchase_details.product_purchase_id')
//            ->leftJoin('products','products.id','=','product_purchase_details.product_id')
//            ->where('product_purchases.store_id',$store_id)
//            ->groupBy('product_purchase_details.product_id')
//            ->groupBy('products.barcode')
//            ->get();
        $products = DB::table('stocks')
            ->select('stocks.product_id','products.barcode')
            //->join('product_purchases','product_purchases.id','=','product_purchase_details.product_purchase_id')
            ->join('products','products.id','=','stocks.product_id')
            ->where('stocks.store_id',$store_id)
            ->groupBy('stocks.product_id')
            ->groupBy('products.barcode')
            ->get();

        if(count($products) > 0){

//            $options = '';
//            foreach($products as $data){
//                $product_name = \App\Product::where('id',$data->product_id)->pluck('name')->first();
//                $product_price = \App\ProductPurchaseDetail::where('product_id',$data->product_id)->latest()->pluck('mrp_price')->first();
//                $product_current_stock = \App\Stock::where('product_id',$data->product_id)->latest()->pluck('current_stock')->first();
//                $options .= "<tr>";
//                $options .= "<th>".$data->barcode."</th>";
//                $options .= "<th>".$product_name."</th>";
//                $options .= "<th>".$product_price."</th>";
//                $options .= "<th>".$product_current_stock."</th>";
//                $options .= '<th><a onclick="selectItem('.$data->barcode.')" class="btn btn-primary"><i class="fa fa-check-circle"></i> Select</a></th>';
//                $options .= "</tr>";
//            }


                $options = '<table id="example1" class="table table-bordered table-striped">';
                $options .= "<thead>";
                $options .= "<tr>";
                $options .= "<th>Barcode</th>";
                $options .= "<th>Product Name</th>";
                $options .= "<th>Purchase Price</th>";
                $options .= "<th>Stock Qty</th>";
                $options .= "<th>Action</th>";
                $options .= "</tr>";
                $options .= "</thead>";
                $options .= "</tbody>";
            foreach($products as $data){
                $product_name = \App\Product::where('id',$data->product_id)->pluck('name')->first();
                $product_price = \App\ProductPurchaseDetail::where('product_id',$data->product_id)->latest()->pluck('mrp_price')->first();
                $product_current_stock = \App\Stock::where('product_id',$data->product_id)->where('store_id',$store_id)->latest()->pluck('current_stock')->first();
                $options .= "<tr>";
                $options .= "<th>".$data->barcode."</th>";
                $options .= "<th>".$product_name."</th>";
                $options .= "<th>".$product_price."</th>";
                $options .= "<th>".$product_current_stock."</th>";
                $options .= '<th><a onclick="selectItem('.$data->barcode.')" class="btn btn-primary"><i class="fa fa-check-circle"></i> Select</a></th>';
                $options .= "</tr>";
            }
                $options .= "</tbody>";
                $options .= "</table>";


        }else{
            $options = "No Data Found!";
        }

        return response()->json(['success'=>true,'data'=>$options]);
    }

    public function create()
    {
        $auth = Auth::user();
        $auth_user = Auth::user()->roles[0]->name;
        $parties = Party::where('type','customer')->get() ;
        $store_id = 1;
        if($auth_user == "Admin"){
            $stores = Store::all();
//            $products = DB::table('product_purchase_details')
//                ->select('product_purchase_details.product_id','product_purchase_details.barcode')
//                ->leftJoin('products','products.id','=','product_purchase_details.product_id')
//                ->groupBy('product_purchase_details.product_id')
//                ->groupBy('product_purchase_details.barcode')
//                ->get();

            $products = DB::table('product_purchase_details')
                ->select('product_purchase_details.product_id','product_purchase_details.barcode')
                ->join('product_purchases','product_purchases.id','=','product_purchase_details.product_purchase_id')
                ->leftJoin('products','products.id','=','product_purchase_details.product_id')
                ->where('product_purchases.store_id',$store_id)
                ->groupBy('product_purchase_details.product_id')
                ->groupBy('product_purchase_details.barcode')
                ->get();
        }else{
            $stores = Store::where('id',$auth->store_id)->get();
            $store_id = $stores[0]->id;

            //$stores = Store::where('user_id',$auth_user_id)->first();
            //$store_id = $stores->id;

            //dd($store_id);


            $products = DB::table('product_purchase_details')
                ->select('product_purchase_details.product_id','product_purchase_details.barcode')
                ->join('product_purchases','product_purchases.id','=','product_purchase_details.product_purchase_id')
                ->leftJoin('products','products.id','=','product_purchase_details.product_id')
                ->where('product_purchases.store_id',$store_id)
                ->groupBy('product_purchase_details.product_id')
                ->groupBy('product_purchase_details.barcode')
                ->get();
        }
        $productCategories = ProductCategory::all();
        $productSubCategories = ProductSubCategory::all();
        $productBrands = ProductBrand::all();

       // dd($stores);

        return view('backend.productPosSale.create',compact('parties','stores','products','productCategories','productSubCategories','productBrands','store_id'));
    }

    public function selectedform($barcode, $store_id){

        $baseurl = URL('/pos_insert');

        // One Way

//        $html = '<form name="form" id="form" action="'.$baseurl.'" method="post" enctype="multipart/form-data">
//                <input type="hidden" name="_token" value="'.csrf_token().'" />
//                    <table class="table table-striped tabel-penjualan">
//                        <thead>
//                            <tr>
//                                <th width="30">No</th>
//                                <th>Barcode</th>
//                                <th>Product Name</th>
//                                <th align="right">Price</th>
//                                <th>Quantity</th>
//                                <th align="right">Sub Total</th>
//                                <th>Action</th>
//                            </tr>
//                        </thead>
//                        <tbody></tbody>
//                    </table>
//                    <div class="row">
//                        <div class="col-md-8">
//
//                        </div>
//                        <div class="col-md-4">
//                            <div class="form-group row">
//                                <label for="totalrp" class="col-md-4 control-label">Sub Total</label>
//                                <div class="col-md-8">
//                                    <input type="text" class="form-control" id="totalrp" readonly>
//                                </div>
//                            </div>
//                            <div class="form-group row">
//                                <label for="member" class="col-md-4 control-label">Customer</label>
//                                <div class="col-md-8">
//                                    <div class="input-group">
//                                        <input id="member" type="text" class="form-control" name="member" value="0">
//                                        <span class="input-group-btn">
//                                          <button onclick="showMember()" type="button" class="btn btn-info">...</button>
//                                        </span>
//                                    </div>
//                                </div>
//                            </div>
//                            <div class="form-group row">
//                                <label for="diskon" class="col-md-4 control-label">Discount</label>
//                                <div class="col-md-8">
//                                    <input type="text" class="form-control" name="diskon" id="diskon" value="0">
//                                </div>
//                            </div>
//                            <div class="form-group row">
//                                <label for="bayarrp" class="col-md-4 control-label">Grand Total</label>
//                                <div class="col-md-8">
//                                    <input type="text" class="form-control" name="bayarrp" id="diskon" value="0" readonly>
//                                </div>
//                            </div>
//                            <div class="form-group row">
//                                <label for="diterima" class="col-md-4 control-label">Paid</label>
//                                <div class="col-md-8">
//                                    <input type="number" class="form-control" value="0" name="diterima" id="diterima">
//                                </div>
//                            </div>
//                            <div class="form-group row">
//                                <label for="kembali" class="col-md-4 control-label">Due</label>
//                                <div class="col-md-8">
//                                    <input type="text" class="form-control" id="kembali" value="0" readonly>
//                                </div>
//                            </div>
//                            <div class="box-footer">
//                                <button type="submit" class="btn btn-primary pull-right simpan"><i class="fa fa-floppy-o"></i> Save</button>
//                            </div>
//                        </div>
//                    </div>
//            </form>';



        $html = "<form name=\"form\" id=\"form\" action=\"".$baseurl."\" method=\"post\" enctype=\"multipart/form-data\">
                    <div class=\"form-group row\">
                    <div class=\"col-md-8\">
                    <input type=\"hidden\" name=\"_token\" value=\"".csrf_token()."\" />
                    <input type=\"hidden\" name=\"store_id\" value=\"".$store_id."\" />
                    <table class=\"table table-striped tabel-penjualan\">
                        <thead>
                            <tr>
                                <th width=\"30\">No</th>
                                <th>Barcode</th>
                                <th>Product Name</th>
                                <th align=\"right\">Price</th>
                                <th>Quantity</th>
                                <th align=\"right\">Sub Total</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>";
        if(Cart::count() > 0):
            foreach(Cart::content() as $item):
                $html .= "<tr>";
                $html .= "<th width=\"30\">1</th>";
                $html .= "<th>".$item->options['barcode']."</th>";
                $html .= "<th>".$item->name."</th>";
                $html .= "<th align=\"right\">".$item->price."</th>";
                //$html .= "<th><input type=\"text\" value=\"".$item->qty."\" size=\"28\" </th>";
                $html .= "<th>".$item->qty."</th>";
                $html .= "<th align=\"right\">".$item->price."</th>";
                $html .= "<th><input type=\"button\" class=\"btn btn-warning\" name=\"remove\" id=\"remove\" size=\"28\" value=\"Remove\" onClick=\"deleteCart('$item->rowId')\" /></th>";
                $html .= "</tr>";
            endforeach;
            $html .= "<tr><th align=\"right\" colspan=\"7\"><input type=\"button\" class=\"btn btn-danger\" name=\"remove\" id=\"remove\" size=\"28\" value=\"Clear Item\" onClick=\"deleteAllCart()\" /></th></tr>";
        endif;
        $html .= "</tbody>
                    </table>
                    </div>

                    <div class=\"col-md-4\">
                        <div class=\"form-group row\">
                            <label for=\"sub_total\" class=\"col-md-4 control-label\">Sub Total</label>
                            <div class=\"col-md-8\">
                                <input type=\"text\" class=\"form-control\" id=\"sub_total\" value=\"".Cart::subtotal()."\" readonly>
                            </div>
                        </div>
                        <!--<div class=\"form-group row\">
                            <label for=\"member\" class=\"col-md-4 control-label\">Customer</label>
                            <div class=\"col-md-8\">
                                <div class=\"input-group\">
                                    <input id=\"member\" type=\"text\" class=\"form-control\" name=\"customer\" value=\"\">
                                    <span class=\"input-group-btn\">
                                      <button onclick=\"showMember()\" type=\"button\" class=\"btn btn-info\">select</button>
                                    </span>
                                </div>
                            </div>
                        </div>-->
                        <div class=\"form-group row\">
                            <label for=\"member\" class=\"col-md-4 control-label\">Customer Phone</label>
                            <div class=\"col-md-8\">
                                <div class=\"input-group\">
                                    <input id=\"member\" type=\"text\" class=\"form-control\" name=\"customer\" value=\"\" placeholder=\"01700000000\">
                                </div>
                            </div>
                        </div>
                        <div class=\"form-group row\">
                            <label for=\"vat_amount\" class=\"col-md-4 control-label\">Vat(Percentage)</label>
                            <div class=\"col-md-8\">
                                <input type=\"number\" class=\"form-control\" name=\"vat_amount\" id=\"vat_amount\" onkeyup=\"vatAmount('')\" value=\"0\">
                            </div>
                        </div>
                        <div class=\"form-group row\">
                            <label for=\"discount_amount\" class=\"col-md-4 control-label\">Discount(Flat)</label>
                            <div class=\"col-md-8\">
                                <input type=\"number\" class=\"form-control\" name=\"discount_amount\" id=\"discount_amount\" onkeyup=\"discountAmount('')\" value=\"0\">
                            </div>
                        </div>
                        <div class=\"form-group row\">
                            <label for=\"grand_total\" class=\"col-md-4 control-label\">Grand Total</label>
                            <div class=\"col-md-8\">
                                    <input type=\"hidden\" class=\"form-control\" id=\"store_grand_total\" value=\"".Cart::subtotal()."\" readonly>
                                <input type=\"text\" class=\"form-control\" name=\"grand_total\" id=\"grand_total\" value=\"".Cart::subtotal()."\" readonly>
                            </div>
                        </div>
                        <div class=\"form-group row\">
                            <label for=\"paid_amount\" class=\"col-md-4 control-label\">Paid</label>
                            <div class=\"col-md-8\">
                                <input type=\"number\" class=\"form-control\" value=\"0\" name=\"paid_amount\" onkeyup=\"paidAmount('')\" id=\"paid_amount\">
                            </div>
                        </div>
                        <div class=\"form-group row\">
                            <label for=\"due_amount\" class=\"col-md-4 control-label\">Due</label>
                            <div class=\"col-md-8\">
                                <input type=\"text\" class=\"form-control\" id=\"due_amount\" name=\"due_amount\" value=\"".Cart::subtotal()."\" readonly>
                            </div>
                        </div>
                        <div class=\"form-group row\">
                            <label for=\"due_amount\" class=\"col-md-4 control-label\">Payment Type</label>
                            <div class=\"col-md-8\">
                                <select class=\"form-control\" id=\"payment_type\" name=\"payment_type\">
                                    <option value=\"cash\">cash</option>
                                    <option value=\"credit\">credit</option>
                                    <option value=\"card\">card</option>
                                    <option value=\"bkash\">bkash</option>
                                </select>
                            </div>
                        </div>
                        <div class=\"box-footer\">
                        <div class=\"col-md-8\">
                            <button type=\"submit\" class=\"btn btn-primary pull-right simpan\"><i class=\"fa fa-floppy-o\"></i> Save</button>
                        </div>
                    </div>
                    </div>
            </form>";
        echo json_encode($html);

    }

    public function postInsert(Request $request){
        //dd($request->all());
        $customer = isset($request->customer) ? $request->customer : 'Mr.';
        if(is_numeric($customer) && strlen($customer) > 9){
            $customer_check_exits = Party::where('phone',$customer)->pluck('id')->first();
            if($customer_check_exits){
                $customer_id = $customer_check_exits;
            }else{
                $parties = new Party();
                $parties->type = 'customer';
                $parties->name = 'Mr.';
                $parties->phone = $customer;
                $parties->slug = 'Mr.'.Str::slug($customer);
                $parties->email = '';
                $parties->address = '';
                $parties->status = 1;
                $parties->save();
                $customer_id = $parties->id;
            }
        }elseif(is_string($customer)){
            if($customer == 'Mr.'){
                $customer_check_exits = Party::where('name',$customer)->pluck('id')->first();
                if($customer_check_exits){
                    $customer_id = $customer_check_exits;
                }else{
                    $parties = new Party();
                    $parties->type = 'customer';
                    $parties->name = $customer;
                    $parties->slug = Str::slug($customer);
                    $parties->phone = '01700000000';
                    $parties->email = '';
                    $parties->address = '';
                    $parties->status = 1;
                    $parties->save();
                    $customer_id = $parties->id;
                }
            }else{
                $customer_check_exits = Party::where('id',$customer)->pluck('id')->first();
                if($customer_check_exits){
                    $customer_id = $customer_check_exits;
                }else{
                    $parties = new Party();
                    $parties->type = 'customer';
                    $parties->name = $customer;
                    $parties->slug = Str::slug($customer);
                    $parties->phone = '01700000000';
                    $parties->email = '';
                    $parties->address = '';
                    $parties->status = 1;
                    $parties->save();
                    $customer_id = $parties->id;
                }
            }
        }else{
            $customer_id = $request->customer;
        }





//        echo $customer_id;
//        echo '<pre>';
//        print_r($request->all());
//        echo '</pre>';
//        dd(Cart::content());


        //$total_amount = Cart::subtotal();
        $vat_amount = $request->vat_amount;
        $discount_amount = $request->discount_amount;
        $total_amount = $request->grand_total;
        $paid_amount = $request->paid_amount;
        $due_amount = $request->due_amount;
        $payment_type = $request->payment_type;



        $get_invoice_no = ProductSale::latest()->pluck('invoice_no')->first();
        //dd($get_invoice_no);
        if(!empty($get_invoice_no)){
            $get_invoice = str_replace("sale-","",$get_invoice_no);
            //$invoice_no = $get_invoice_no+1;
            $invoice_no = $get_invoice+1;
        }else{
            $invoice_no = 1000;
        }
        //dd($invoice_no);

        // product purchase
        $productSale = new ProductSale();
        $productSale->invoice_no = 'sale-'.$invoice_no;
        $productSale->user_id = Auth::id();
        $productSale->party_id = $customer_id;
        $productSale->store_id = $request->store_id;
        $productSale->date = date('Y-m-d');
        $productSale->delivery_service_id = NULL;
        $productSale->delivery_service_charge = 0;
        $productSale->vat_type = 'percentage';
        $productSale->vat_amount = $vat_amount;
        $productSale->discount_type = 'flat';
        $productSale->discount_amount = $discount_amount;
        $productSale->total_amount = $total_amount;
        $productSale->paid_amount = $paid_amount;
        $productSale->due_amount = $due_amount;
        $productSale->sale_type = 'pos';
        $productSale->save();
        $insert_id = $productSale->id;
        if($insert_id)
        {
            foreach (Cart::content() as $content) {
                $product = Product::where('id',$content->id)->first();

                $discount = ($discount_amount*$content->price)/($discount_amount+$total_amount);
                $discount = round($discount);

                // product purchase detail
                $purchase_sale_detail = new ProductSaleDetail();
                $purchase_sale_detail->product_sale_id = $insert_id;
                $purchase_sale_detail->return_type = 'not returnable';
                $purchase_sale_detail->product_category_id = $product->product_category_id;
                $purchase_sale_detail->product_sub_category_id = $product->product_sub_category_id ? $product->product_sub_category_id : NULL;
                $purchase_sale_detail->product_brand_id = $product->product_brand_id;
                $purchase_sale_detail->product_id = $content->id;
                $purchase_sale_detail->qty = $content->qty;
                $purchase_sale_detail->price = $content->price;
                $purchase_sale_detail->discount = $discount;
                $purchase_sale_detail->sub_total = $content->qty*$content->price;
                $purchase_sale_detail->save();

                $check_previous_stock = Stock::where('product_id',$content->id)->latest()->pluck('current_stock')->first();
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
                $stock->date = date('Y-m-d');
                $stock->product_id = $content->id;
                $stock->stock_type = 'sale';
                $stock->previous_stock = $previous_stock;
                $stock->stock_in = 0;
                $stock->stock_out = $content->qty;
                $stock->current_stock = $previous_stock - $content->qty;
                $stock->save();
            }

            // due
            $due = new Due();
            $due->invoice_no = 'sale-'.$invoice_no;
            $due->ref_id = $insert_id;
            $due->user_id = Auth::id();
            $due->store_id = 1;
            $due->party_id = $customer_id;
            $due->total_amount = $total_amount;
            $due->paid_amount = $request->paid_amount;
            $due->due_amount = $request->due_amount;
            $due->save();

            // transaction
            $transaction = new Transaction();
            $transaction->invoice_no = 'sale-'.$invoice_no;
            $transaction->user_id = Auth::id();
            $transaction->store_id = $request->store_id;
            $transaction->party_id = $customer_id;
            $transaction->date = date('Y-m-d');
            $transaction->ref_id = $insert_id;
            $transaction->transaction_type = 'sale';
            $transaction->payment_type = $payment_type;
            $transaction->check_number = '';
            $transaction->amount = $total_amount;
            $transaction->save();

            // session add product sale id
            Session::put('product_sale_id',$insert_id);

            Toastr::success('Order Successfully done! ');
            Cart::destroy();
            return back();
        }else{
            Toastr::warning('Something went wrong! ');
            Cart::destroy();
            return back();
        }
    }

}

<?php

namespace App\Http\Controllers;

use App\Party;
use App\Product;
use App\ProductBrand;
use App\ProductCategory;
use App\ProductPurchaseDetail;
use App\ProductSubCategory;
use App\Stock;
use App\Store;
use Gloudemans\Shoppingcart\Facades\Cart;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ProductPosPurchaseController extends Controller
{
    public function create(){
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
        return view('backend.productPosPurchase.create',compact('parties','stores','products','productCategories','productSubCategories','productBrands'));
    }

    public function selectedform(){

        $html = "<table class=\"table table-striped\">
                        <thead>
                            <tr>
                                <th >ID</th>
                                <th>Product <small class=\"requiredCustom\">*</small></th>
                                <th>Category</th>
                                <th>Sub Category</th>
                                <th>Brand</th>
                                <th>Qty <small class=\"requiredCustom\">*</small></th>
                                <th>Purchase Price <small class=\"requiredCustom\">*</small></th>
                                <th>Selling Price <small class=\"requiredCustom\">*</small></th>
                                <th>Sub Total</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody class=\"neworderbody\">

                        </tbody>
                    </table>";
        echo json_encode($html);

    }

    public function appendToRow(Request $request){
        $barcode = $request->barcode;
        $data = array();
        if($barcode){
            $product_check_exists = Product::where('barcode',$barcode)->first();
            if($product_check_exists){

                $product_category_check_exists = ProductCategory::where('id',$product_check_exists->product_category_id)->first();
                if(!empty($product_category_check_exists)){
                    $product_category_id = $product_category_check_exists->id;
                    $product_category_name = $product_category_check_exists->name;
                }else{
                    $product_category_id = '';
                    $product_category_name = '';
                }
                $product_sub_category_check_exists = ProductSubCategory::where('id',$product_check_exists->product_sub_category_id)->first();
                if(!empty($product_sub_category_check_exists)){
                    $product_sub_category_id = $product_sub_category_check_exists->id;
                    $product_sub_category_name = $product_sub_category_check_exists->name;
                }else{
                    $product_sub_category_id = '';
                    $product_sub_category_name = '';
                }
                $product_brand_check_exists = ProductBrand::where('id',$product_check_exists->product_brand_id)->first();
                if(!empty($product_brand_check_exists)){
                    $product_brand_id = $product_brand_check_exists->id;
                    $product_brand_name = $product_brand_check_exists->name;
                }else{
                    $product_brand_id = '';
                    $product_brand_name = '';
                }

                $data['product_check_exists'] = 'Product Found!';
                $row = "<tr>
                            <td class=\"no\">1</td>
                            <td style=\"width:120px\"><select class=\"form-control\"><option value='\"".$product_check_exists->id."\"'>".$product_check_exists->name."</option></select></td>
                            <td style=\"width:120px\"><select class=\"form-control\"><option value='\"".$product_category_id."\"'>".$product_category_name."</option></select></td>
                            <td style=\"width:120px\"><select class=\"form-control\"><option value='\"".$product_sub_category_id."\"'>".$product_sub_category_name."</option></select></td>
                            <td style=\"width:120px\"><select class=\"form-control\"><option value='\"".$product_brand_id."\"'>".$product_brand_name."</option></select></td>
                            <td><input type=\"number\" min=\"1\" class=\"qty form-control\" name=\"qty[]\" required></td>
                            <td><input type=\"number\" min=\"1\" class=\"price form-control\" name=\"price[]\" required></td>
                            <td><input type=\"number\" min=\"1\" class=\"form-control\" name=\"mrp_price[]\" required></td>
                            <td><input type=\"number\" class=\"amount form-control\" name=\"sub_total[]\" required></td>
                            <td><input type=\"button\" class=\"btn btn-danger delete\" value=\"x\" required></td>
                        </tr>";

                $data['row'] = $row;


            }else{
                $data['product_check_exists'] = 'No Product Found!';
            }

        }
        return response()->json(['success'=> true, 'response'=>$data]);
    }
}

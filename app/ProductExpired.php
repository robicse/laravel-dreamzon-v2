<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ProductExpired extends Model
{
    public function user(){
        return $this->belongsTo('App\User');
    }

    public function store()
    {
        return $this->belongsTo('App\Store');
    }

    public function productPurchase()
    {
        return $this->belongsTo('App\ProductPurchase');
    }

    public function productPurchaseDetail()
    {
        return $this->belongsTo('App\ProductPurchaseDetail');
    }

    public function productCategory()
    {
        return $this->belongsTo('App\ProductCategory');
    }

    public function productSubCategory()
    {
        return $this->belongsTo('App\ProductSubCategory');
    }

    public function productBrand()
    {
        return $this->belongsTo('App\ProductBrand');
    }

    public function product()
    {
        return $this->belongsTo('App\Product');
    }
}

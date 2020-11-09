<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ProductSale extends Model
{
    public function user(){
        return $this->belongsTo('App\User');
    }

    public function store()
    {
        return $this->belongsTo('App\Store');
    }

    public function party()
    {
        return $this->belongsTo('App\Party');
    }

    public function deliveryService()
    {
        return $this->belongsTo('App\DeliveryService');
    }
}

<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = [
        'code',
        'total_price',
        'price'
    ];

    public function products(){
        return $this->belongsToMany(Product::class);
    }
}

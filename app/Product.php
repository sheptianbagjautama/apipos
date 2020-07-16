<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable =[
        'name',
        'original_price',
        'discount_price',
        'image',
    ];

    public function orders(){
        return $this->belongsToMany(Order::class);
    }
}

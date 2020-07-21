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
        'stock',
        'category_id',
        'sub_category_id',
    ];

    public function orders(){
        return $this->belongsToMany(Order::class);
    }

    public function category(){
        return $this->belongsTo(Category::class);
    }

    public function sub_category(){
        return $this->belongsTo(Subcategory::class);
    }
}

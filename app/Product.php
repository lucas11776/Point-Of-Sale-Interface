<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'category_id', 'sub_category_id', 'name', 'slug', 'brand', 'in_stock', 'price', 'discount'
    ];
}

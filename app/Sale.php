<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Sale extends Model
{
    /**
     * Model that are allowed as saleables in application.
     *
     * @var array
     */
    public const SALEABLES = [
        Product::class
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'transaction_id', 'saleable_type', 'saleable_id', 'price', 'quantity'
    ];
}

<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\MorphOne;

class Transaction extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id', 'employee_id', 'customer_id', 'device', 'price', 'payed'
    ];

    /**
     * Get transaction sales.
     *
     * @return HasMany
     */
    public function sales(): HasMany
    {
        return $this->hasMany(Sale::class);
    }

    /**
     * Get transaction order.
     *
     * @return MorphOne
     */
    public function order(): MorphOne
    {
        return $this->morphOne(Order::class, 'orderizable');
    }

    /**
     * Get transactions orders.
     *
     * @return MorphMany
     */
    public function attachements(): MorphMany
    {
        return $this->morphMany(Attachments::class, 'attachmentable');
    }
}

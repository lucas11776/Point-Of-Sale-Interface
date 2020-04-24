<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphMany;

//'declined',    # Order has been declined.
//        'waiting',     # Order is waiting to be approved.
//        'pending',     # Order is currently on hold waiting to be worked on.
//        'in_progress', # Employee is currently working on your order.
//        'completed',   # Order is complete waiting to be collected or delivered.
//        'collected',   # Order is collected by the customer.
//        'delivered',   # Order has been delivered to customer.
//        'received',    # Order has been received by customer.

class Order extends Model
{
    /**
     * Order status for the application.
     *
     * @var array
     */
    public const STATUS = [
        'declined',    # Order has been declined.
        'waiting',     # Order is waiting to be approved.
        'pending',     # Order is currently on hold waiting to be worked on.
        'in_progress', # Employee is currently working on your order.
        'completed',   # Order is complete waiting to be collected or delivered.
        'collected',
        'delivered',
        'received'
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'orderizable_type', 'orderizable_id', 'user_id', 'customer_id', 'deadline', 'status', 'message'
    ];

    /**
     * Get order attachments.
     *
     * @return MorphMany
     */
    public function attachements(): MorphMany
    {
        return $this->morphMany(Attachments::class, 'attachmentable');
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Payment extends Model
{
    protected $primaryKey = 'payment_id'; // Explicitly define primary key
    protected $fillable = [
        'payment_date',
        'amount',
        'payment_method',
        'reference_no',
    ];

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class, 'reference_no', 'reference_no');
    }

    protected static function boot()
    {
        parent::boot();

        static::created(function ($payment) {
            $payment->order->update([
                'payment_status' => 1, // Set Payment Status to "Success"
                'delivery_status' => 1, // Set Delivery Status to "Ready To Deliver"
            ]);
        });
        static::deleted(function ($payment) {
            $order = $payment->order;
            if ($order && $order->payments()->count() === 0) {
                $order->update([
                    'payment_status' => 0,
                    'delivery_status' => '0',
                ]);
            }
        });
    }
}

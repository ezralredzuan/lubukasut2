<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $table = 'orders'; // Explicitly define table name
    protected $primaryKey = 'order_id'; // Define primary key

    public $incrementing = true; // Ensure auto-incrementing works
    protected $keyType = 'int'; // Define primary key type

    protected $fillable = [
        'reference_no',
        'no_phone',
        'address',
        'city',
        'state',
        'postal_code',
        'order_date',
        'payment_status',
        'delivery_status',
        'shipped_date',
        'price',
        'customer_id',
        'product_id',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($order) {
            $order->reference_no = self::generateReferenceNumber();
        });
    }

    protected static function generateReferenceNumber()
    {
        $lastOrder = self::orderBy('order_id', 'desc')->first(); // Use order_id instead of id
        $nextNumber = $lastOrder ? intval(substr($lastOrder->reference_no, 3)) + 1 : 1;
        return 'REF' . str_pad($nextNumber, 5, '0', STR_PAD_LEFT);
    }

    // Define relationships
    public function customer()
    {
        return $this->belongsTo(Customer::class, 'customer_id');
    }

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }
}

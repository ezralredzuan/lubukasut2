<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $table = 'products';

    protected $fillable = [
        'product_image',
        'gender',
        'name',
        'size',
        'price',
        'description',
        'brand_id',
        'staff_id',
    ];

    /**
     * Get the brand that owns the product.
     */
    public function brand()
    {
        return $this->belongsTo(Brand::class, 'brand_id', 'id'); // Ensure correct FK
    }

    /**
     * Get the staff that registered the product.
     */
    public function staff()
    {
        return $this->belongsTo(Staff::class, 'staff_id');
    }

    /**
     * Handle image storage (optional: if storing file path instead of BLOB).
     */
    public function getProductImageUrl()
    {
        return $this->product_image ? asset('storage/' . $this->product_image) : null;
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Hash;


class Customer extends Model
{
    use HasFactory;

    protected $table = 'customers';
    protected $primaryKey = 'id'; // Ensure this matches your database
    public $timestamps = true; // Enables created_at and updated_at

    protected $fillable = ['username', 'password', 'full_name', 'no_phone', 'email', 'address', 'city', 'state', 'postal_code'];

    public static function boot()
    {
        parent::boot();
        static::creating(function ($customer) {
            if ($customer->password) {
                $customer->password = Hash::make($customer->password);
            }
        });
    }

    protected $hidden = ['Password']; // Hide password field when retrieving data

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];
}

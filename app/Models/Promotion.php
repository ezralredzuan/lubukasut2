<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Promotion extends Model
{
    use HasFactory;

    protected $table = 'promotions';
    protected $primaryKey = 'promotion_id';

    protected $fillable = [
        'name',
        'promotion_code',
        'discount_percentage',
        'valid_until_date',
        'staff_id',
    ];

    public function staff()
    {
        return $this->belongsTo(Staff::class, 'staff_id');
    }
}

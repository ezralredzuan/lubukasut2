<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Permission\Traits\HasRoles;

class Staff extends Model
{
    use HasFactory, HasRoles;

    protected $table = 'staff';
    protected $fillable = [
        'username',
        'password',
        'staff_pic',
        'staff_name',
        'address',
        'no_phone',
        'email',
        'hired_date',
        'role'
    ];

    protected $hidden = ['password'];

    protected $casts = [
        'hired_date' => 'date',
    ];
}


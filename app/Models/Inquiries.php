<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Inquiries extends Model
{
    use HasFactory;

    protected $table = 'inquiries';

    protected $fillable = [
        'email',
        'inquiries_title',
        'description',
    ];
}

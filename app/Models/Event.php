<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    use HasFactory;

    protected $table = 'events';

    protected $primaryKey = 'event_id';

    public $timestamps = true;

    public $fillable = [
        'title',
        'status',
        'staff_id',
        'content',
        'image_cover',
    ];

    /**
     * Relationship with Staff model
     */
    public function staff()
    {
        return $this->belongsTo(Staff::class, 'staff_id', 'id');
    }
}

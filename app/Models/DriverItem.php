<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DriverItem extends Model
{
    protected $fillable = [
        'cars_id',
        'updated_by',
        'safety_items',
        'standard_items',
        'status',
        'image_evidence',
    ];

    public function car()
    {
        return $this->belongsTo(Car::class, 'cars_id');
    }

    public function updatedBy()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }
}

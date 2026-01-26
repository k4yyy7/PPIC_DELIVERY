<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Environment extends Model
{
    protected $fillable = [
        'safety_items',
        'standard_items',
        'status',
        'updated_by',
        'cars_id',
    ];

    public function updatedBy()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    public function car()
    {
        return $this->belongsTo(Car::class, 'cars_id');
    }
}

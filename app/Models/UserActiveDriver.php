<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserActiveDriver extends Model
{
    protected $fillable = [
        'user_id', 'driver_item_id', 'driver_id', 'date'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function driver()
    {
        return $this->belongsTo(\App\Models\Driver::class, 'driver_id');
    }
}

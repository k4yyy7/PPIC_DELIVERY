<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DailyReport extends Model
{
    protected $fillable = [
        'date',
        'user_id',
        'subject_type',
        'subject_id',
        'status',
        'image_path',
        'notes',
        'driver_name',
    ];

    protected $casts = [
        'date' => 'date',
    ];

    /**
     * Relasi ke item yang diisi pada report ini (polymorphic ke subject)
     */
    public function itemObject()
    {
        // Mengembalikan instance item terkait (DriverItem, ArmadaItem, Dokument, Environment, Safety)
        return $this->morphTo(__FUNCTION__, 'subject_type', 'subject_id');
    }

    /**
     * Helper: return 1 untuk setiap report (karena 1 report = 1 item)
     */
    public function getItemCountAttribute()
    {
        return 1;
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function subject(): MorphTo
    {
        return $this->morphTo();
    }
}

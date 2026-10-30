<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BookingPhoto extends Model
{
    /**
     * @var list<string>
     */
    protected $fillable = ['paint_booking_id', 'stage', 'path', 'caption', 'uploaded_by'];

    /**
     * @return BelongsTo<PaintBooking, $this>
     */
    public function booking(): BelongsTo
    {
        return $this->belongsTo(PaintBooking::class, 'paint_booking_id');
    }
}

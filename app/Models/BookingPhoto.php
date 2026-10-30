<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable(['paint_booking_id', 'stage', 'path', 'caption', 'uploaded_by'])]
class BookingPhoto extends Model
{
    /**
     * @return BelongsTo<PaintBooking, $this>
     */
    public function booking(): BelongsTo
    {
        return $this->belongsTo(PaintBooking::class, 'paint_booking_id');
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PaintBooking extends Model
{
    /**
     * @var list<string>
     */
    protected $fillable = [
        'public_id',
        'service_number',
        'customer_name',
        'customer_email',
        'customer_phone',
        'vehicle_make',
        'vehicle_model',
        'vehicle_year',
        'vehicle_color',
        'service_type',
        'description',
        'preferred_date',
        'status',
    ];

    public const STATUSES = [
        'requested' => 'Request received',
        'confirmed' => 'Confirmed',
        'in_progress' => 'Work in progress',
        'completed' => 'Completed',
        'cancelled' => 'Cancelled',
    ];

    public const PHOTO_STAGES = [
        'before' => 'Before',
        'during' => 'During',
        'after' => 'After',
    ];

    public const SERVICES = [
        'full_repaint' => 'Full repaint',
        'panel_repaint' => 'Panel repaint',
        'scratch_repair' => 'Scratch repair',
        'color_change' => 'Color change',
        'custom_paint' => 'Custom paint',
    ];

    protected function casts(): array
    {
        return [
            'preferred_date' => 'date',
        ];
    }

    /**
     * @return HasMany<BookingPhoto, $this>
     */
    public function photos(): HasMany
    {
        return $this->hasMany(BookingPhoto::class);
    }
}

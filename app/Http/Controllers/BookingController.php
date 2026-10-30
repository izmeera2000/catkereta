<?php

namespace App\Http\Controllers;

use App\Models\PaintBooking;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class BookingController extends Controller
{
    public function create(): View
    {
        return view('bookings.create', [
            'services' => PaintBooking::SERVICES,
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'customer_name' => ['required', 'string', 'max:120'],
            'customer_email' => ['required', 'email', 'max:255'],
            'customer_phone' => ['required', 'string', 'max:40'],
            'vehicle_make' => ['required', 'string', 'max:80'],
            'vehicle_model' => ['required', 'string', 'max:80'],
            'vehicle_year' => ['nullable', 'integer', 'between:1900,'.(now()->year + 1)],
            'vehicle_color' => ['nullable', 'string', 'max:80'],
            'service_type' => ['required', Rule::in(array_keys(PaintBooking::SERVICES))],
            'description' => ['nullable', 'string', 'max:3000'],
            'preferred_date' => ['nullable', 'date', 'after_or_equal:today'],
        ]);

        $booking = PaintBooking::create([
            ...$validated,
            'public_id' => (string) Str::uuid(),
            'service_number' => $this->generateServiceNumber(),
            'status' => 'requested',
        ]);

        return redirect()->to(URL::signedRoute('bookings.show', ['booking' => $booking->public_id], null, false))
            ->with('booking_created', true);
    }

    public function show(PaintBooking $booking): View
    {
        $booking->load('photos');

        return view('bookings.show', compact('booking'));
    }

    private function generateServiceNumber(): string
    {
        do {
            $serviceNumber = 'SRV-'.Str::upper(Str::random(12));
        } while (PaintBooking::query()->where('service_number', $serviceNumber)->exists());

        return $serviceNumber;
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\PaintBooking;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\URL;

class BookingTrackingController extends Controller
{
    public function __invoke(Request $request): RedirectResponse
    {
        $serviceNumber = $request->input('service_number');

        $request->merge([
            'service_number' => is_string($serviceNumber)
                ? strtoupper(trim($serviceNumber))
                : $serviceNumber,
        ]);

        $validated = $request->validate([
            'service_number' => ['required', 'string', 'regex:/\ASRV-[A-Z0-9]{12}\z/'],
        ]);

        $booking = PaintBooking::query()
            ->where('service_number', $validated['service_number'])
            ->first();

        if ($booking === null) {
            return back()
                ->withErrors(['service_number' => 'We could not find a service with that number. Check it and try again.'])
                ->withInput();
        }

        return redirect()->to(URL::signedRoute(
            'bookings.show',
            ['booking' => $booking->public_id],
            null,
            false,
        ));
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\BookingPhoto;
use App\Models\PaintBooking;
use App\Services\BookingPhotoCompressor;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\View\View;
use RuntimeException;

class StaffBookingController extends Controller
{
    public function index(): View
    {
        return view('staff.bookings.index', [
            'bookings' => PaintBooking::query()->latest()->paginate(15),
            'statuses' => PaintBooking::STATUSES,
            'counts' => PaintBooking::query()
                ->selectRaw('status, count(*) as total')
                ->groupBy('status')
                ->pluck('total', 'status'),
        ]);
    }

    public function show(PaintBooking $paintBooking): View
    {
        $paintBooking->load('photos');

        return view('staff.bookings.show', [
            'booking' => $paintBooking,
            'statuses' => PaintBooking::STATUSES,
            'stages' => PaintBooking::PHOTO_STAGES,
        ]);
    }

    public function update(Request $request, PaintBooking $paintBooking): RedirectResponse
    {
        $validated = $request->validate([
            'status' => ['required', Rule::in(array_keys(PaintBooking::STATUSES))],
        ]);

        $paintBooking->update($validated);

        return back()->with('success', 'Booking status updated.');
    }

    public function storePhoto(
        Request $request,
        PaintBooking $paintBooking,
        BookingPhotoCompressor $compressor,
    ): RedirectResponse {
        if ($paintBooking->photos()->count() >= 100) {
            return back()->withErrors(['photo' => 'This booking has reached the 100-photo limit.']);
        }

        $validated = $request->validate([
            'stage' => ['required', Rule::in(array_keys(PaintBooking::PHOTO_STAGES))],
            'photo' => ['required', 'image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
            'caption' => ['nullable', 'string', 'max:255'],
        ]);

        $compressed = $compressor->compress($request->file('photo'));
        $path = 'bookings/'.$paintBooking->id.'/'.Str::uuid().'.'.$compressed['extension'];

        if (! Storage::disk('local')->put($path, $compressed['contents'])) {
            throw new RuntimeException('The booking photo could not be saved to private storage.');
        }

        $paintBooking->photos()->create([
            'stage' => $validated['stage'],
            'path' => $path,
            'caption' => $validated['caption'] ?? null,
            'uploaded_by' => $request->user()->id,
        ]);

        return back()->with('success', 'Progress photo uploaded.');
    }

    public function photo(PaintBooking $booking, BookingPhoto $photo)
    {
        abort_unless($photo->paint_booking_id === $booking->id, 404);

        return Storage::disk('local')->response($photo->path);
    }
}

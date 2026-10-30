<?php

namespace Tests\Feature;

use App\Models\BookingPhoto;
use App\Models\PaintBooking;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\URL;
use Tests\TestCase;

class PaintBookingTest extends TestCase
{
    use RefreshDatabase;

    public function test_customer_can_submit_a_paint_booking_and_get_a_private_tracking_link(): void
    {
        $response = $this->post(route('bookings.store'), [
            'customer_name' => 'Sam Customer',
            'customer_email' => 'sam@example.com',
            'customer_phone' => '+1 555 0100',
            'vehicle_make' => 'Toyota',
            'vehicle_model' => 'Corolla',
            'vehicle_year' => 2020,
            'vehicle_color' => 'Blue',
            'service_type' => 'panel_repaint',
            'preferred_date' => now()->addWeek()->toDateString(),
            'description' => 'Please repaint the front passenger door.',
        ]);

        $booking = PaintBooking::query()->sole();

        $response->assertRedirect(URL::signedRoute(
            'bookings.show',
            ['booking' => $booking->public_id],
            null,
            false,
        ));
        $this->assertSame('requested', $booking->status);

        $this->get(URL::signedRoute(
            'bookings.show',
            ['booking' => $booking->public_id],
            null,
            false,
        ))
            ->assertOk()
            ->assertSee('Request received.')
            ->assertSee('Toyota Corolla');

        $this->get(route('bookings.show', ['booking' => $booking->public_id]))
            ->assertForbidden();
    }

    public function test_booking_rejects_an_invalid_service_and_a_past_date(): void
    {
        $this->from(route('bookings.create'))
            ->post(route('bookings.store'), [
                'customer_name' => 'Sam Customer',
                'customer_email' => 'sam@example.com',
                'customer_phone' => '+1 555 0100',
                'vehicle_make' => 'Toyota',
                'vehicle_model' => 'Corolla',
                'service_type' => 'free_repaint',
                'preferred_date' => now()->subDay()->toDateString(),
            ])
            ->assertRedirect(route('bookings.create'))
            ->assertSessionHasErrors(['service_type', 'preferred_date']);

        $this->assertDatabaseCount('paint_bookings', 0);
    }

    public function test_only_staff_can_manage_bookings_and_progress_photos(): void
    {
        $booking = PaintBooking::create([
            'public_id' => fake()->uuid(),
            'customer_name' => 'Sam Customer',
            'customer_email' => 'sam@example.com',
            'customer_phone' => '+1 555 0100',
            'vehicle_make' => 'Toyota',
            'vehicle_model' => 'Corolla',
            'service_type' => 'panel_repaint',
            'status' => 'requested',
        ]);

        $this->get(route('staff.dashboard'))->assertRedirect(route('staff.login'));

        $this->actingAs(User::factory()->create(['is_staff' => false]))
            ->get(route('staff.dashboard'))
            ->assertForbidden();

        $staff = User::factory()->create(['is_staff' => true]);
        Storage::fake('local');

        $this->actingAs($staff)
            ->get(route('staff.dashboard'))
            ->assertOk()
            ->assertSee('Sam Customer');

        $this->actingAs($staff)
            ->patch(route('staff.bookings.update', $booking), ['status' => 'confirmed'])
            ->assertRedirect();

        $this->assertDatabaseHas('paint_bookings', [
            'id' => $booking->id,
            'status' => 'confirmed',
        ]);

        $upload = UploadedFile::fake()->image('before.jpg', 3000, 1500);

        $this->actingAs($staff)
            ->post(route('staff.bookings.photos.store', $booking), [
                'stage' => 'before',
                'photo' => $upload,
                'caption' => 'Arrival condition',
            ])
            ->assertRedirect()
            ->assertSessionHasNoErrors();

        $photo = BookingPhoto::query()->sole();
        Storage::disk('local')->assertExists($photo->path);
        $this->assertSame($staff->id, $photo->uploaded_by);

        $storedImage = getimagesizefromstring(Storage::disk('local')->get($photo->path));
        $this->assertNotFalse($storedImage);
        $this->assertSame('image/webp', $storedImage['mime']);
        $this->assertLessThanOrEqual(2000, max($storedImage[0], $storedImage[1]));
        $this->assertSame([2000, 1000], [$storedImage[0], $storedImage[1]]);
        $this->assertLessThan(filesize($upload->getRealPath()), strlen(Storage::disk('local')->get($photo->path)));
        $this->assertSame('webp', pathinfo($photo->path, PATHINFO_EXTENSION));

        $photoUrl = URL::signedRoute('bookings.photos.show', [
            'booking' => $booking->public_id,
            'photo' => $photo->id,
        ], null, false);

        $this->get($photoUrl)->assertOk();
        $this->get(route('bookings.photos.show', [
            'booking' => $booking->public_id,
            'photo' => $photo->id,
        ]))->assertForbidden();
    }

    public function test_staff_login_accepts_only_staff_accounts(): void
    {
        $customer = User::factory()->create([
            'email' => 'customer@example.com',
            'password' => 'a-strong-password',
            'is_staff' => false,
        ]);

        $this->post(route('staff.login.store'), [
            'email' => $customer->email,
            'password' => 'a-strong-password',
        ])
            ->assertRedirect()
            ->assertSessionHasErrors('email');

        $this->assertGuest();

        $staff = User::factory()->create([
            'email' => 'staff@example.com',
            'password' => 'a-strong-password',
            'is_staff' => true,
        ]);

        $this->post(route('staff.login.store'), [
            'email' => $staff->email,
            'password' => 'a-strong-password',
        ])
            ->assertRedirect(route('staff.dashboard'));

        $this->assertAuthenticatedAs($staff);
    }
}

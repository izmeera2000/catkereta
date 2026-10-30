<?php

namespace Tests\Feature;

use App\Models\BookingPhoto;
use App\Models\PaintBooking;
use App\Models\User;
use Database\Seeders\DatabaseSeeder;
use Database\Seeders\StaffUserSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\URL;
use Spatie\Permission\Models\Role;
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
        $this->assertMatchesRegularExpression('/\ASRV-[A-Z0-9]{12}\z/', $booking->service_number);

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
            ->assertSee($booking->service_number)
            ->assertSee('Toyota Corolla');

        $this->get(route('bookings.show', ['booking' => $booking->public_id]))
            ->assertForbidden();
    }

    public function test_customer_can_track_a_booking_using_only_its_service_number(): void
    {
        $booking = PaintBooking::create([
            'public_id' => fake()->uuid(),
            'service_number' => 'SRV-ABC123DEF456',
            'customer_name' => 'Sam Customer',
            'customer_email' => 'sam@example.com',
            'customer_phone' => '+1 555 0100',
            'vehicle_make' => 'Toyota',
            'vehicle_model' => 'Corolla',
            'service_type' => 'panel_repaint',
            'status' => 'requested',
        ]);

        $this->get(route('bookings.create'))
            ->assertOk()
            ->assertSee('Already have a service number?')
            ->assertSee(route('bookings.track'));

        $this->post(route('bookings.track'), [
            'service_number' => strtolower($booking->service_number),
        ])
            ->assertRedirect(URL::signedRoute(
                'bookings.show',
                ['booking' => $booking->public_id],
                null,
                false,
            ));

        $this->get(URL::signedRoute(
            'bookings.show',
            ['booking' => $booking->public_id],
            null,
            false,
        ))
            ->assertOk()
            ->assertSee('Toyota Corolla');
    }

    public function test_unknown_service_number_does_not_reveal_booking_details(): void
    {
        $this->from(route('bookings.create'))
            ->post(route('bookings.track'), [
                'service_number' => 'SRV-000000000000',
            ])
            ->assertRedirect(route('bookings.create'))
            ->assertSessionHasErrors([
                'service_number' => 'We could not find a service with that number. Check it and try again.',
            ]);
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
            'service_number' => 'SRV-ABC123DEF456',
            'customer_name' => 'Sam Customer',
            'customer_email' => 'sam@example.com',
            'customer_phone' => '+1 555 0100',
            'vehicle_make' => 'Toyota',
            'vehicle_model' => 'Corolla',
            'service_type' => 'panel_repaint',
            'status' => 'requested',
        ]);

        $this->get(route('staff.dashboard'))->assertRedirect(route('staff.login'));

        $this->actingAs(User::factory()->create())
            ->get(route('staff.dashboard'))
            ->assertForbidden();

        $staff = User::factory()->create();
        $staff->assignRole(Role::findOrCreate('staff', 'web'));
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
        ]);
        $staff->assignRole(Role::findOrCreate('staff', 'web'));

        $this->post(route('staff.login.store'), [
            'email' => $staff->email,
            'password' => 'a-strong-password',
        ])
            ->assertRedirect(route('staff.dashboard'));

        $this->assertAuthenticatedAs($staff);
    }

    public function test_database_seeder_includes_staff_account_without_overwriting_existing_credentials(): void
    {
        config(['staff.seeded_user' => [
            'name' => 'Shop Manager',
            'email' => 'manager@example.com',
            'password' => 'a-strong-password',
        ]]);

        $this->seed(DatabaseSeeder::class);
        $user = User::query()->where('email', 'manager@example.com')->sole();

        $this->assertTrue($user->hasRole('staff'));
        $this->assertTrue(Hash::check('a-strong-password', $user->password));

        config(['staff.seeded_user.password' => 'another-strong-password']);
        $this->seed(DatabaseSeeder::class);

        $this->assertDatabaseCount('users', 2);
        $this->assertTrue(Hash::check('a-strong-password', $user->fresh()->password));
    }

    public function test_staff_seeder_assigns_a_role_without_overwriting_an_existing_password(): void
    {
        $customer = User::factory()->create([
            'email' => 'customer@example.com',
            'password' => 'customer-password',
        ]);

        config(['staff.seeded_user' => [
            'name' => 'Shop Manager',
            'email' => 'customer@example.com',
            'password' => 'a-new-strong-password',
        ]]);

        $this->seed(StaffUserSeeder::class);

        $customer->refresh();
        $this->assertTrue($customer->hasRole('staff'));
        $this->assertTrue(Hash::check('customer-password', $customer->password));
    }
}

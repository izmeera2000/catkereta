<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules\Password;
use RuntimeException;
use Spatie\Permission\Models\Role;

class StaffUserSeeder extends Seeder
{
    public function run(): void
    {
        $staff = config('staff.seeded_user');
        $validator = Validator::make($staff, [
            'name' => ['required', 'string', 'max:120'],
            'email' => ['required', 'email', 'max:255'],
            'password' => ['required', 'string', Password::min(12)],
        ]);

        if ($validator->fails()) {
            throw new RuntimeException(
                'Unable to seed staff user: '.$validator->errors()->first()
                .' Configure STAFF_USER_NAME, STAFF_USER_EMAIL, and STAFF_USER_PASSWORD in .env.'
            );
        }

        $user = User::query()->firstOrCreate(
            ['email' => $staff['email']],
            [
                'name' => $staff['name'],
                'password' => $staff['password'],
            ],
        );

        $user->assignRole(Role::findOrCreate('staff', 'web'));
    }
}

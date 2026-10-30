<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules\Password;
use Spatie\Permission\Models\Role;

class CreateStaffAccount extends Command
{
    protected $signature = 'make:staff {name} {email}';

    protected $description = 'Create a staff account for managing paint bookings';

    public function handle(): int
    {
        $name = (string) $this->argument('name');
        $email = (string) $this->argument('email');

        $validator = Validator::make(
            ['name' => $name, 'email' => $email],
            ['name' => ['required', 'string', 'max:120'], 'email' => ['required', 'email', 'max:255', 'unique:users,email']],
        );

        if ($validator->fails()) {
            $this->components->error($validator->errors()->first());

            return self::FAILURE;
        }

        $password = $this->secret('Staff password (at least 12 characters)');

        $passwordValidator = Validator::make(
            ['password' => $password],
            ['password' => ['required', 'string', Password::min(12)]],
        );

        if ($passwordValidator->fails()) {
            $this->components->error($passwordValidator->errors()->first());

            return self::FAILURE;
        }

        $user = User::create([
            'name' => $name,
            'email' => $email,
            'password' => Hash::make($password),
        ]);
        $user->assignRole(Role::findOrCreate('staff', 'web'));

        $this->components->info("Staff account created for {$email}.");

        return self::SUCCESS;
    }
}

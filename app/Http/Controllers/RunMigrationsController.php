<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Artisan;
use Illuminate\View\View;

class RunMigrationsController extends Controller
{
    public function show(): View
    {
        abort_unless(config('deployment.migrations_route_enabled'), 404);

        return view('maintenance.migrate');
    }

    public function store(): RedirectResponse
    {
        abort_unless(config('deployment.migrations_route_enabled'), 404);

        $exitCode = Artisan::call('migrate', ['--force' => true]);
        $output = trim(Artisan::output());

        if ($exitCode !== 0) {
            return back()->withErrors([
                'migration' => $output !== ''
                    ? $output
                    : 'Migrations did not complete successfully. Check the application logs.',
            ]);
        }

        return back()->with(
            'migration_output',
            $output !== '' ? $output : 'Migrations completed successfully.',
        );
    }
}

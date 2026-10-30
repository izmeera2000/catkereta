<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Artisan;
use Tests\TestCase;

class TemporaryMigrationRouteTest extends TestCase
{
    use RefreshDatabase;

    public function test_migration_route_is_disabled_by_default(): void
    {
        $this->get(route('maintenance.migrations.show'))
            ->assertNotFound();

        $this->post(route('maintenance.migrations.store'))
            ->assertNotFound();
    }

    public function test_enabled_route_runs_only_pending_migrations_on_post(): void
    {
        config(['deployment.migrations_route_enabled' => true]);
        Artisan::shouldReceive('call')
            ->once()
            ->with('migrate', ['--force' => true])
            ->andReturn(0);
        Artisan::shouldReceive('output')
            ->once()
            ->andReturn('Nothing to migrate.');

        $this->get(route('maintenance.migrations.show'))
            ->assertOk()
            ->assertSee('MIGRATIONS_ROUTE_ENABLED=true')
            ->assertSee(route('maintenance.migrations.store'));

        $this->assertGuest();

        $this->post(route('maintenance.migrations.store'))
            ->assertRedirect(route('maintenance.migrations.show'))
            ->assertSessionHas('migration_output', 'Nothing to migrate.');
    }

    public function test_migration_failure_is_reported_to_the_page(): void
    {
        config(['deployment.migrations_route_enabled' => true]);
        Artisan::shouldReceive('call')
            ->once()
            ->with('migrate', ['--force' => true])
            ->andReturn(1);
        Artisan::shouldReceive('output')
            ->once()
            ->andReturn('Migration failed.');

        $this->from(route('maintenance.migrations.show'))
            ->post(route('maintenance.migrations.store'))
            ->assertRedirect(route('maintenance.migrations.show'))
            ->assertSessionHasErrors(['migration' => 'Migration failed.']);
    }
}

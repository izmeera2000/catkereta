<?php

namespace Tests\Feature;

// use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ExampleTest extends TestCase
{
    /**
     * A basic test example.
     */
    public function test_the_application_returns_a_successful_response(): void
    {
        $response = $this->get('/');

        $response->assertOk()
            ->assertSee('Request a paint booking')
            ->assertSee('vehicle_make')
            ->assertSee('assets/css/dashlite.css')
            ->assertSee('assets/js/scripts.js');
    }
}

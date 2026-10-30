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
            ->assertSee('Bring your paintwork back to life.')
            ->assertSee('/book-service')
            ->assertSee('/track-service')
            ->assertSee('assets/css/dashlite.css')
            ->assertSee('assets/js/scripts.js');
    }
}

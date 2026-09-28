<?php

namespace Tests\Feature;

use Tests\TestCase;

class SplashScreenTest extends TestCase
{
    public function test_the_root_route_displays_the_splash_screen(): void
    {
        $response = $this->get('/');

        $response
            ->assertOk()
            ->assertViewIs('splash')
            ->assertSee('Fresh from local farms.')
            ->assertSee('Connecting farmers and communities.')
            ->assertSee(route('marketplace.home'), escape: false)
            ->assertSee('images/brand/marketlink-logo.jpg');

        $this->assertFileExists(public_path('images/brand/marketlink-logo.jpg'));
    }

    public function test_the_marketplace_placeholder_is_available(): void
    {
        $response = $this->get(route('marketplace.home'));

        $response
            ->assertOk()
            ->assertViewIs('public.marketplace-placeholder')
            ->assertSee('MarketLink')
            ->assertSee('Homepage coming next.');
    }
}

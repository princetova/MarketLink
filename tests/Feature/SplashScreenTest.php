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

    public function test_the_public_marketplace_homepage_is_available(): void
    {
        $response = $this->get(route('marketplace.home'));

        $response
            ->assertOk()
            ->assertViewIs('public.home')
            ->assertSee('Fresh from local farms.')
            ->assertSee('Explore Fresh Produce')
            ->assertSee('Organic Tomatoes')
            ->assertSee('Local Markets Near You', escape: false)
            ->assertSee('Meet Your Local Farmers', escape: false)
            ->assertSee('MarketLink is pickup only', escape: false)
            ->assertSee('Join as a Customer')
            ->assertSee('images/marketplace/hero-farmer.jpg')
            ->assertSee('videos/marketplace/marketlink-hero.mp4');

        $this->assertFileExists(public_path('images/marketplace/hero-farmer.jpg'));
        $this->assertFileExists(public_path('images/marketplace/organic-tomatoes.jpg'));
        $this->assertFileExists(public_path('videos/marketplace/marketlink-hero.mp4'));
    }
}

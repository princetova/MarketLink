<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\RateLimiter;
use Tests\TestCase;

class AuthenticationTest extends TestCase
{
    use RefreshDatabase;

    protected function tearDown(): void
    {
        RateLimiter::clear('person@example.com|127.0.0.1');

        parent::tearDown();
    }

    public function test_login_screen_can_be_rendered(): void
    {
        $response = $this->get(route('login'));

        $response
            ->assertOk()
            ->assertViewIs('auth.login')
            ->assertSee('Welcome back')
            ->assertSee('Sign in to continue to MarketLink.')
            ->assertSee('name="_token"', escape: false)
            ->assertSee('autocomplete="email"', escape: false)
            ->assertSee('autocomplete="current-password"', escape: false)
            ->assertSee(route('login.store'), escape: false)
            ->assertSee('images/brand/marketlink-logo.jpg')
            ->assertSee('images/marketplace/local-farmers.jpg');
    }

    public function test_guest_can_authenticate_with_normalized_email(): void
    {
        $user = User::factory()->create([
            'email' => 'person@example.com',
            'password' => Hash::make('market-fresh-password'),
        ]);

        $response = $this->post(route('login.store'), [
            'email' => '  PERSON@EXAMPLE.COM ',
            'password' => 'market-fresh-password',
        ]);

        $this->assertAuthenticatedAs($user);
        $response->assertRedirect(route('marketplace.home'));
    }

    public function test_successful_authentication_regenerates_the_session(): void
    {
        $user = User::factory()->create([
            'email' => 'person@example.com',
            'password' => Hash::make('market-fresh-password'),
        ]);
        $this->withSession(['login-marker' => 'before-login']);
        $this->get(route('login'));
        $sessionIdBeforeLogin = session()->getId();

        $this->post(route('login.store'), [
            'email' => $user->email,
            'password' => 'market-fresh-password',
        ]);

        $this->assertNotSame($sessionIdBeforeLogin, session()->getId());
    }

    public function test_invalid_credentials_are_rejected_with_a_generic_error(): void
    {
        User::factory()->create([
            'email' => 'person@example.com',
            'password' => Hash::make('correct-password'),
        ]);

        $response = $this->from(route('login'))->post(route('login.store'), [
            'email' => 'person@example.com',
            'password' => 'incorrect-password',
        ]);

        $this->assertGuest();
        $response
            ->assertRedirect(route('login'))
            ->assertSessionHasErrors([
                'email' => 'The provided credentials do not match our records.',
            ]);
    }

    public function test_remember_me_creates_a_recaller_cookie(): void
    {
        $user = User::factory()->create([
            'email' => 'person@example.com',
            'password' => Hash::make('market-fresh-password'),
        ]);
        $recallerName = Auth::guard('web')->getRecallerName();

        $response = $this->post(route('login.store'), [
            'email' => $user->email,
            'password' => 'market-fresh-password',
            'remember' => '1',
        ]);

        $this->assertAuthenticatedAs($user);
        $response->assertCookie($recallerName);
    }

    public function test_repeated_failed_logins_are_rate_limited(): void
    {
        for ($attempt = 0; $attempt < 5; $attempt++) {
            $this->post(route('login.store'), [
                'email' => 'person@example.com',
                'password' => 'incorrect-password',
            ]);
        }

        $response = $this->from(route('login'))->post(route('login.store'), [
            'email' => 'person@example.com',
            'password' => 'incorrect-password',
        ]);

        $response
            ->assertRedirect(route('login'))
            ->assertSessionHasErrors('email');

        $this->assertStringContainsString(
            'Too many sign-in attempts.',
            session('errors')->first('email'),
        );
    }

    public function test_authenticated_user_can_logout(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->post(route('logout'));

        $this->assertGuest();
        $response->assertRedirect(route('marketplace.home'));
    }

    public function test_logout_requires_authentication(): void
    {
        $this->post(route('logout'))
            ->assertRedirect(route('login'));
    }

    public function test_homepage_sign_in_link_resolves_to_login(): void
    {
        $this->get(route('marketplace.home'))
            ->assertOk()
            ->assertSee('href="'.route('login').'"', escape: false);
    }
}

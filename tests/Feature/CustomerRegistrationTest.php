<?php

namespace Tests\Feature;

use App\Models\CustomerProfile;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Route;
use RuntimeException;
use Tests\TestCase;

class CustomerRegistrationTest extends TestCase
{
    use RefreshDatabase;

    public function test_customer_registration_screen_is_available_to_guests(): void
    {
        $response = $this->get(route('register.customer'));

        $response
            ->assertOk()
            ->assertViewIs('auth.register-customer')
            ->assertSee('Create your customer account')
            ->assertSee('Discover fresh local produce and reserve directly from farmers near you.')
            ->assertSee('Full Name')
            ->assertSee('Email Address')
            ->assertSee('Phone Number')
            ->assertSee('Confirm Password')
            ->assertSee('Create Account')
            ->assertSee(route('register.customer.store'), escape: false)
            ->assertSee(route('login'), escape: false)
            ->assertSee('data-password-toggle', escape: false)
            ->assertSee('data-auth-form', escape: false);
    }

    public function test_customer_can_register_and_is_authenticated(): void
    {
        $response = $this->post(route('register.customer.store'), $this->validPayload([
            'email' => '  CUSTOMER@Example.COM ',
        ]));

        $user = User::query()->where('email', 'customer@example.com')->firstOrFail();

        $response
            ->assertRedirect(route('marketplace.home'))
            ->assertSessionHas('status', 'Welcome to MarketLink. Your customer account is ready.');

        $this->assertAuthenticatedAs($user);
        $this->assertSame(User::ROLE_CUSTOMER, $user->role);
        $this->assertSame('+234 801 234 5678', $user->phone);
        $this->assertTrue(Hash::check('SecurePass1', $user->password));
        $this->assertDatabaseHas('customer_profiles', [
            'user_id' => $user->id,
            'address' => '12 Market Road, Kaduna',
        ]);
        $this->assertSame('12 Market Road, Kaduna', $user->customerProfile?->address);
        $this->assertTrue($user->customerProfile?->user->is($user));
    }

    public function test_customer_role_is_assigned_server_side(): void
    {
        $this->post(route('register.customer.store'), $this->validPayload([
            'email' => 'role-test@example.com',
            'role' => User::ROLE_ADMIN,
        ]))->assertRedirect(route('marketplace.home'));

        $this->assertDatabaseHas('users', [
            'email' => 'role-test@example.com',
            'role' => User::ROLE_CUSTOMER,
        ]);
        $this->assertDatabaseMissing('users', [
            'email' => 'role-test@example.com',
            'role' => User::ROLE_ADMIN,
        ]);
    }

    public function test_registration_requires_every_customer_field(): void
    {
        foreach (['name', 'email', 'phone', 'address', 'password'] as $field) {
            $payload = $this->validPayload(['email' => "missing-{$field}@example.com"]);
            unset($payload[$field]);

            if ($field === 'password') {
                unset($payload['password_confirmation']);
            }

            $this->from(route('register.customer'))
                ->post(route('register.customer.store'), $payload)
                ->assertRedirect(route('register.customer'))
                ->assertSessionHasErrors($field);
        }

        $this->assertDatabaseCount('users', 0);
        $this->assertDatabaseCount('customer_profiles', 0);
    }

    public function test_registration_rejects_duplicate_email_invalid_phone_and_unconfirmed_password(): void
    {
        User::factory()->create(['email' => 'taken@example.com']);

        $this->from(route('register.customer'))
            ->post(route('register.customer.store'), $this->validPayload([
                'email' => ' TAKEN@example.com ',
                'phone' => 'not-a-phone!',
                'password_confirmation' => 'DifferentPass1',
            ]))
            ->assertRedirect(route('register.customer'))
            ->assertSessionHasErrors(['email', 'phone', 'password']);

        $this->assertDatabaseCount('customer_profiles', 0);
    }

    public function test_user_and_profile_creation_are_atomic(): void
    {
        $this->withoutExceptionHandling();

        CustomerProfile::creating(static function (): never {
            throw new RuntimeException('Simulated profile failure.');
        });

        try {
            $this->post(route('register.customer.store'), $this->validPayload([
                'email' => 'rollback@example.com',
            ]));
            $this->fail('The simulated profile failure did not occur.');
        } catch (RuntimeException $exception) {
            $this->assertSame('Simulated profile failure.', $exception->getMessage());
        } finally {
            CustomerProfile::flushEventListeners();
        }

        $this->assertDatabaseMissing('users', ['email' => 'rollback@example.com']);
        $this->assertDatabaseCount('customer_profiles', 0);
    }

    public function test_registration_routes_are_guest_only(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)
            ->get(route('register.customer'))
            ->assertRedirect(route('splash'));

        $this->actingAs($user)
            ->post(route('register.customer.store'), $this->validPayload())
            ->assertRedirect(route('splash'));

        $this->assertDatabaseCount('users', 1);
        $this->assertDatabaseCount('customer_profiles', 0);
    }

    public function test_customer_links_are_live_and_farmer_registration_remains_unavailable(): void
    {
        $this->get(route('marketplace.home'))
            ->assertOk()
            ->assertSee(route('register.customer'), escape: false)
            ->assertSee('Join as a Customer')
            ->assertSee('data-coming-soon="Farmer account"', escape: false);

        $this->get(route('login'))
            ->assertOk()
            ->assertSee(route('register.customer'), escape: false)
            ->assertSee('Join as a Customer')
            ->assertSee('data-auth-future="Farmer registration"', escape: false);

        $this->assertFalse(Route::has('register.farmer'));
        $this->get('/register/farmer')->assertNotFound();
    }

    public function test_existing_users_can_still_sign_in_without_profile_data(): void
    {
        $user = User::factory()->create([
            'email' => 'existing@example.com',
            'password' => Hash::make('ExistingPass1'),
        ]);

        $this->post(route('login.store'), [
            'email' => 'existing@example.com',
            'password' => 'ExistingPass1',
        ])->assertRedirect(route('marketplace.home'));

        $this->assertAuthenticatedAs($user);
        $this->assertNull($user->phone);
        $this->assertNull($user->role);
        $this->assertNull($user->customerProfile);
    }

    /** @param array<string, string> $overrides
     * @return array<string, string>
     */
    private function validPayload(array $overrides = []): array
    {
        return array_merge([
            'name' => 'Amina Bello',
            'email' => 'customer@example.com',
            'phone' => '+234 801 234 5678',
            'address' => '12 Market Road, Kaduna',
            'password' => 'SecurePass1',
            'password_confirmation' => 'SecurePass1',
        ], $overrides);
    }
}

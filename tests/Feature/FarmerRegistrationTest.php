<?php

namespace Tests\Feature;

use App\Models\FarmerProfile;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Route;
use RuntimeException;
use Tests\TestCase;

class FarmerRegistrationTest extends TestCase
{
    use RefreshDatabase;

    public function test_farmer_registration_screen_is_available_to_guests(): void
    {
        $response = $this->get(route('register.farmer'));

        $response
            ->assertOk()
            ->assertViewIs('auth.register-farmer')
            ->assertSee('Create your farmer account')
            ->assertSee('Join MarketLink and connect your farm with customers looking for fresh local produce.')
            ->assertSee('Full Name')
            ->assertSee('Farm / Business Name')
            ->assertSee('Email Address')
            ->assertSee('Phone Number')
            ->assertSee('Confirm Password')
            ->assertSee('Create Farmer Account')
            ->assertSee(route('register.farmer.store'), escape: false)
            ->assertSee(route('login'), escape: false)
            ->assertSee('data-password-toggle', escape: false)
            ->assertSee('autocomplete="new-password"', escape: false)
            ->assertDontSee('name="role"', escape: false)
            ->assertDontSee('name="approval_status"', escape: false)
            ->assertDontSee('Main Market');
    }

    public function test_farmer_can_register_with_a_pending_profile_and_is_authenticated(): void
    {
        $response = $this->post(route('register.farmer.store'), $this->validPayload([
            'email' => '  FARMER@Example.COM ',
        ]));

        $user = User::query()->where('email', 'farmer@example.com')->firstOrFail();

        $response
            ->assertRedirect(route('marketplace.home'))
            ->assertSessionHas('status', 'Your farmer account has been created and is pending approval.');

        $this->assertAuthenticatedAs($user);
        $this->assertSame(User::ROLE_FARMER, $user->role);
        $this->assertSame('+234 802 345 6789', $user->phone);
        $this->assertTrue(Hash::check('SecurePass1', $user->password));
        $this->assertDatabaseHas('farmer_profiles', [
            'user_id' => $user->id,
            'business_name' => 'Green Valley Farm',
            'address' => '24 Farm Road, Kaduna',
            'approval_status' => FarmerProfile::STATUS_PENDING,
        ]);
        $this->assertSame('Green Valley Farm', $user->farmerProfile?->business_name);
        $this->assertSame('24 Farm Road, Kaduna', $user->farmerProfile?->address);
        $this->assertTrue($user->farmerProfile?->user->is($user));
    }

    public function test_successful_farmer_registration_regenerates_the_session(): void
    {
        $this->withSession(['registration-marker' => 'before-registration']);
        $this->get(route('register.farmer'));
        $sessionIdBeforeRegistration = session()->getId();

        $this->post(route('register.farmer.store'), $this->validPayload());

        $this->assertNotSame($sessionIdBeforeRegistration, session()->getId());
    }

    public function test_farmer_role_and_pending_status_are_assigned_server_side(): void
    {
        $this->post(route('register.farmer.store'), $this->validPayload([
            'email' => 'security-test@example.com',
            'role' => User::ROLE_ADMIN,
            'approval_status' => FarmerProfile::STATUS_APPROVED,
        ]))->assertRedirect(route('marketplace.home'));

        $user = User::query()->where('email', 'security-test@example.com')->firstOrFail();

        $this->assertSame(User::ROLE_FARMER, $user->role);
        $this->assertSame(FarmerProfile::STATUS_PENDING, $user->farmerProfile?->approval_status);
        $this->assertNotSame(User::ROLE_ADMIN, $user->role);
        $this->assertNotSame(FarmerProfile::STATUS_APPROVED, $user->farmerProfile?->approval_status);
    }

    public function test_registration_requires_every_farmer_field(): void
    {
        foreach (['name', 'business_name', 'email', 'phone', 'address', 'password'] as $field) {
            $payload = $this->validPayload(['email' => "missing-{$field}@example.com"]);
            unset($payload[$field]);

            if ($field === 'password') {
                unset($payload['password_confirmation']);
            }

            $this->from(route('register.farmer'))
                ->post(route('register.farmer.store'), $payload)
                ->assertRedirect(route('register.farmer'))
                ->assertSessionHasErrors($field);
        }

        $this->assertDatabaseCount('users', 0);
        $this->assertDatabaseCount('farmer_profiles', 0);
    }

    public function test_registration_rejects_duplicate_or_invalid_email_invalid_phone_and_unconfirmed_password(): void
    {
        User::factory()->create(['email' => 'taken@example.com']);

        $this->from(route('register.farmer'))
            ->post(route('register.farmer.store'), $this->validPayload([
                'email' => ' TAKEN@example.com ',
                'phone' => 'not-a-phone!',
                'password_confirmation' => 'DifferentPass1',
            ]))
            ->assertRedirect(route('register.farmer'))
            ->assertSessionHasErrors(['email', 'phone', 'password']);

        $this->from(route('register.farmer'))
            ->post(route('register.farmer.store'), $this->validPayload([
                'email' => 'invalid-email',
            ]))
            ->assertSessionHasErrors('email');

        $this->assertDatabaseCount('farmer_profiles', 0);
    }

    public function test_user_and_farmer_profile_creation_are_atomic(): void
    {
        $this->withoutExceptionHandling();

        FarmerProfile::creating(static function (): never {
            throw new RuntimeException('Simulated farmer profile failure.');
        });

        try {
            $this->post(route('register.farmer.store'), $this->validPayload([
                'email' => 'rollback@example.com',
            ]));
            $this->fail('The simulated farmer profile failure did not occur.');
        } catch (RuntimeException $exception) {
            $this->assertSame('Simulated farmer profile failure.', $exception->getMessage());
        } finally {
            FarmerProfile::flushEventListeners();
        }

        $this->assertDatabaseMissing('users', ['email' => 'rollback@example.com']);
        $this->assertDatabaseCount('farmer_profiles', 0);
    }

    public function test_farmer_registration_routes_are_guest_only(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)
            ->get(route('register.farmer'))
            ->assertRedirect(route('splash'));

        $this->actingAs($user)
            ->post(route('register.farmer.store'), $this->validPayload())
            ->assertRedirect(route('splash'));

        $this->assertDatabaseCount('users', 1);
        $this->assertDatabaseCount('farmer_profiles', 0);
    }

    public function test_farmer_links_are_live_without_creating_a_dashboard_route(): void
    {
        $this->get(route('marketplace.home'))
            ->assertOk()
            ->assertSee(route('register.farmer'), escape: false)
            ->assertSee('Join as a Farmer')
            ->assertSee(route('register.customer'), escape: false);

        $this->get(route('login'))
            ->assertOk()
            ->assertSee(route('register.farmer'), escape: false)
            ->assertSee('Join as a Farmer')
            ->assertSee(route('register.customer'), escape: false);

        $this->assertFalse(Route::has('farmer.dashboard'));
        $this->get('/farmer/dashboard')->assertNotFound();
    }

    public function test_customer_registration_continues_to_create_only_a_customer_profile(): void
    {
        $this->post(route('register.customer.store'), [
            'name' => 'Amina Bello',
            'email' => 'customer-still-works@example.com',
            'phone' => '+234 801 234 5678',
            'address' => '12 Market Road, Kaduna',
            'password' => 'SecurePass1',
            'password_confirmation' => 'SecurePass1',
        ])->assertRedirect(route('marketplace.home'));

        $user = User::query()->where('email', 'customer-still-works@example.com')->firstOrFail();

        $this->assertSame(User::ROLE_CUSTOMER, $user->role);
        $this->assertNotNull($user->customerProfile);
        $this->assertNull($user->farmerProfile);
    }

    /**
     * @param  array<string, string>  $overrides
     * @return array<string, string>
     */
    private function validPayload(array $overrides = []): array
    {
        return array_merge([
            'name' => 'Musa Ibrahim',
            'business_name' => 'Green Valley Farm',
            'email' => 'farmer@example.com',
            'phone' => '+234 802 345 6789',
            'address' => '24 Farm Road, Kaduna',
            'password' => 'SecurePass1',
            'password_confirmation' => 'SecurePass1',
        ], $overrides);
    }
}

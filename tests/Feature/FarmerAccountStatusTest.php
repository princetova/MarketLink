<?php

namespace Tests\Feature;

use App\Models\FarmerProfile;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Route;
use Tests\TestCase;

class FarmerAccountStatusTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_is_redirected_to_login(): void
    {
        $this->get(route('farmer.status'))
            ->assertRedirect(route('login'));
    }

    public function test_customer_cannot_access_farmer_status_even_with_a_role_query_parameter(): void
    {
        $customer = User::factory()->create(['role' => User::ROLE_CUSTOMER]);

        $this->actingAs($customer)
            ->get(route('farmer.status', ['role' => User::ROLE_FARMER]))
            ->assertForbidden();
    }

    public function test_admin_cannot_access_farmer_status(): void
    {
        $admin = User::factory()->create(['role' => User::ROLE_ADMIN]);

        $this->actingAs($admin)
            ->get(route('farmer.status'))
            ->assertForbidden();
    }

    public function test_pending_farmer_sees_pending_state(): void
    {
        $farmer = $this->createFarmer(FarmerProfile::STATUS_PENDING);

        $this->actingAs($farmer)
            ->get(route('farmer.status'))
            ->assertOk()
            ->assertViewIs('farmer.status')
            ->assertSee('PENDING APPROVAL')
            ->assertSee('Your farmer account is under review')
            ->assertSee('waiting for approval before you can begin selling')
            ->assertSee('products, inventory, pickup availability, and orders')
            ->assertSee(route('marketplace.home'), escape: false)
            ->assertSee('action="'.route('logout').'"', escape: false)
            ->assertSee('method="POST"', escape: false)
            ->assertSee('name="_token"', escape: false);
    }

    public function test_approved_farmer_sees_approved_state_when_visiting_directly(): void
    {
        $farmer = $this->createFarmer(FarmerProfile::STATUS_APPROVED);

        $this->actingAs($farmer)
            ->get(route('farmer.status'))
            ->assertOk()
            ->assertSee('APPROVED')
            ->assertSee('Your farmer account is approved')
            ->assertSee('Your MarketLink farmer account is ready.')
            ->assertSee('Continue to MarketLink');
    }

    public function test_suspended_farmer_sees_suspended_state(): void
    {
        $farmer = $this->createFarmer(FarmerProfile::STATUS_SUSPENDED);

        $this->actingAs($farmer)
            ->get(route('farmer.status'))
            ->assertOk()
            ->assertSee('SUSPENDED')
            ->assertSee('Your farmer account is suspended')
            ->assertSee('selling features is currently unavailable');
    }

    public function test_rejected_farmer_sees_rejected_state(): void
    {
        $farmer = $this->createFarmer(FarmerProfile::STATUS_REJECTED);

        $this->actingAs($farmer)
            ->get(route('farmer.status'))
            ->assertOk()
            ->assertSee('NOT APPROVED')
            ->assertSee('Your farmer registration was not approved')
            ->assertSee('contact MarketLink for more information');
    }

    public function test_farmer_without_a_profile_is_handled_safely(): void
    {
        $farmer = User::factory()->create(['role' => User::ROLE_FARMER]);

        $this->actingAs($farmer)
            ->get(route('farmer.status'))
            ->assertOk()
            ->assertSee('SETUP INCOMPLETE')
            ->assertSee('Your farmer account setup is incomplete')
            ->assertSee('could not find the Farmer profile');
    }

    public function test_unknown_farmer_status_fails_safely(): void
    {
        $farmer = $this->createFarmer('UNKNOWN');

        $this->actingAs($farmer)
            ->get(route('farmer.status'))
            ->assertOk()
            ->assertSee('STATUS UNAVAILABLE')
            ->assertSee('could not verify your farmer account status');
    }

    public function test_new_farmer_registration_redirects_to_pending_status(): void
    {
        $response = $this->post(route('register.farmer.store'), [
            'name' => 'Musa Ibrahim',
            'business_name' => 'Green Valley Farm',
            'email' => 'new-farmer@example.com',
            'phone' => '+234 802 345 6789',
            'address' => '24 Farm Road, Kaduna',
            'password' => 'SecurePass1',
            'password_confirmation' => 'SecurePass1',
        ]);

        $farmer = User::query()->where('email', 'new-farmer@example.com')->firstOrFail();

        $response
            ->assertRedirect(route('farmer.status'))
            ->assertSessionHas('status', 'Your farmer account has been created and is pending approval.');
        $this->assertAuthenticatedAs($farmer);
        $this->assertSame(User::ROLE_FARMER, $farmer->role);
        $this->assertSame(FarmerProfile::STATUS_PENDING, $farmer->farmerProfile?->approval_status);

        $this->get(route('farmer.status'))
            ->assertOk()
            ->assertSee('Your farmer account has been created and is pending approval.')
            ->assertSee('PENDING APPROVAL');
    }

    public function test_non_approved_farmer_logins_redirect_to_status(): void
    {
        foreach ([
            FarmerProfile::STATUS_PENDING,
            FarmerProfile::STATUS_SUSPENDED,
            FarmerProfile::STATUS_REJECTED,
            'UNKNOWN',
        ] as $index => $status) {
            $farmer = $this->createFarmer($status, "farmer-{$index}@example.com");

            $this->post(route('login.store'), [
                'email' => $farmer->email,
                'password' => 'SecurePass1',
            ])->assertRedirect(route('farmer.status'));

            $this->assertAuthenticatedAs($farmer);
            $this->post(route('logout'))->assertRedirect(route('marketplace.home'));
            $this->assertGuest();
        }
    }

    public function test_farmer_without_profile_login_redirects_to_safe_status_screen(): void
    {
        $farmer = User::factory()->create([
            'role' => User::ROLE_FARMER,
            'email' => 'missing-profile@example.com',
            'password' => Hash::make('SecurePass1'),
        ]);

        $this->post(route('login.store'), [
            'email' => $farmer->email,
            'password' => 'SecurePass1',
        ])->assertRedirect(route('farmer.status'));
    }

    public function test_approved_farmer_login_redirects_to_marketplace_for_now(): void
    {
        $farmer = $this->createFarmer(FarmerProfile::STATUS_APPROVED);

        $this->post(route('login.store'), [
            'email' => $farmer->email,
            'password' => 'SecurePass1',
        ])->assertRedirect(route('marketplace.home'));

        $this->assertAuthenticatedAs($farmer);
    }

    public function test_customer_and_admin_login_continue_to_redirect_to_marketplace(): void
    {
        foreach ([User::ROLE_CUSTOMER, User::ROLE_ADMIN] as $index => $role) {
            $user = User::factory()->create([
                'role' => $role,
                'email' => "role-{$index}@example.com",
                'password' => Hash::make('SecurePass1'),
            ]);

            $this->post(route('login.store'), [
                'email' => $user->email,
                'password' => 'SecurePass1',
            ])->assertRedirect(route('marketplace.home'));

            $this->assertAuthenticatedAs($user);
            $this->post(route('logout'));
        }
    }

    public function test_farmer_dashboard_and_admin_approval_routes_were_not_created(): void
    {
        $this->assertFalse(Route::has('farmer.dashboard'));
        $this->assertFalse(Route::has('admin.farmers.approve'));
        $this->get('/farmer/dashboard')->assertNotFound();
    }

    private function createFarmer(string $status, string $email = 'farmer@example.com'): User
    {
        $farmer = User::factory()->create([
            'role' => User::ROLE_FARMER,
            'email' => $email,
            'password' => Hash::make('SecurePass1'),
        ]);

        $farmer->farmerProfile()->create([
            'business_name' => 'Green Valley Farm',
            'address' => '24 Farm Road, Kaduna',
            'approval_status' => $status,
        ]);

        return $farmer;
    }
}

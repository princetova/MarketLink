<?php

namespace Tests\Feature;

use App\Models\FarmerProfile;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminFarmerApprovalTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_is_redirected_to_login_from_admin_approval_routes(): void
    {
        $profile = $this->createFarmer();

        $this->get(route('admin.farmers.approvals.index'))
            ->assertRedirect(route('login'));
        $this->patch(route('admin.farmers.approvals.update', $profile), [
            'approval_status' => FarmerProfile::STATUS_APPROVED,
        ])->assertRedirect(route('login'));
    }

    public function test_customer_and_farmer_receive_forbidden_responses(): void
    {
        $profile = $this->createFarmer();

        foreach ([User::ROLE_CUSTOMER, User::ROLE_FARMER] as $role) {
            $user = User::factory()->create(['role' => $role]);

            $this->actingAs($user)
                ->get(route('admin.farmers.approvals.index'))
                ->assertForbidden();
            $this->actingAs($user)
                ->patch(route('admin.farmers.approvals.update', $profile), [
                    'approval_status' => FarmerProfile::STATUS_APPROVED,
                ])->assertForbidden();
        }

        $this->assertSame(FarmerProfile::STATUS_PENDING, $profile->fresh()->approval_status);
    }

    public function test_admin_can_view_pending_farmer_information_and_secure_actions(): void
    {
        $admin = $this->createAdmin();
        $profile = $this->createFarmer(
            FarmerProfile::STATUS_PENDING,
            'Green Valley Farm',
            'Musa Ibrahim',
            'musa@example.com',
        );

        $this->actingAs($admin)
            ->get(route('admin.farmers.approvals.index'))
            ->assertOk()
            ->assertViewIs('admin.farmer-approvals.index')
            ->assertSee('Farmer Approvals')
            ->assertSee('Green Valley Farm')
            ->assertSee('Musa Ibrahim')
            ->assertSee('musa@example.com')
            ->assertSee('+234 802 345 6789')
            ->assertSee('24 Farm Road, Kaduna')
            ->assertSee('PENDING')
            ->assertSee('name="_method" value="PATCH"', escape: false)
            ->assertSee('name="_token"', escape: false)
            ->assertSee('data-approval-form', escape: false)
            ->assertSee(route('admin.farmers.approvals.update', $profile), escape: false);
    }

    public function test_search_matches_business_name_farmer_name_and_email(): void
    {
        $admin = $this->createAdmin();
        $this->createFarmer(FarmerProfile::STATUS_PENDING, 'Sunrise Acres', 'Amina Bello', 'amina@example.com');
        $this->createFarmer(FarmerProfile::STATUS_PENDING, 'River Farm', 'Chidi Okafor', 'chidi@example.com');

        foreach (['Sunrise', 'Amina', 'amina@example.com'] as $search) {
            $this->actingAs($admin)
                ->get(route('admin.farmers.approvals.index', ['search' => $search]))
                ->assertOk()
                ->assertSee('Sunrise Acres')
                ->assertDontSee('River Farm');
        }
    }

    public function test_status_filter_returns_only_the_requested_status(): void
    {
        $admin = $this->createAdmin();
        $this->createFarmer(FarmerProfile::STATUS_PENDING, 'Pending Farm', 'Pending Farmer', 'pending@example.com');
        $this->createFarmer(FarmerProfile::STATUS_APPROVED, 'Approved Farm', 'Approved Farmer', 'approved@example.com');

        $this->actingAs($admin)
            ->get(route('admin.farmers.approvals.index', ['status' => FarmerProfile::STATUS_APPROVED]))
            ->assertOk()
            ->assertSee('Approved Farm')
            ->assertDontSee('Pending Farm');
    }

    public function test_list_is_paginated_and_preserves_query_strings(): void
    {
        $admin = $this->createAdmin();

        for ($index = 1; $index <= 21; $index++) {
            $this->createFarmer(
                FarmerProfile::STATUS_PENDING,
                "Paged Farm {$index}",
                "Farmer {$index}",
                "paged-{$index}@example.com",
            );
        }

        $this->actingAs($admin)
            ->get(route('admin.farmers.approvals.index', [
                'search' => 'Paged',
                'status' => FarmerProfile::STATUS_PENDING,
            ]))
            ->assertOk()
            ->assertViewHas('farmerProfiles', fn ($profiles): bool => $profiles->count() === 20
                && $profiles->total() === 21
                && str_contains($profiles->nextPageUrl(), 'search=Paged')
                && str_contains($profiles->nextPageUrl(), 'status=PENDING'))
            ->assertSee('Page 1 of 2');
    }

    public function test_pending_farmer_can_be_approved_with_review_metadata(): void
    {
        $admin = $this->createAdmin();
        $profile = $this->createFarmer();

        $this->actingAs($admin)
            ->patch(route('admin.farmers.approvals.update', $profile), [
                'approval_status' => FarmerProfile::STATUS_APPROVED,
                'review_note' => 'Registration details reviewed.',
            ])
            ->assertRedirect()
            ->assertSessionHas('status', 'Green Valley Farm is now APPROVED.');

        $profile->refresh();

        $this->assertSame(FarmerProfile::STATUS_APPROVED, $profile->approval_status);
        $this->assertSame($admin->id, $profile->reviewed_by);
        $this->assertNotNull($profile->reviewed_at);
        $this->assertSame('Registration details reviewed.', $profile->review_note);
        $this->assertTrue($profile->reviewer->is($admin));
    }

    public function test_pending_farmer_can_be_rejected_when_a_note_is_provided(): void
    {
        $admin = $this->createAdmin();
        $profile = $this->createFarmer();

        $this->actingAs($admin)
            ->patch(route('admin.farmers.approvals.update', $profile), [
                'approval_status' => FarmerProfile::STATUS_REJECTED,
                'review_note' => 'Business details need correction.',
            ])->assertRedirect();

        $this->assertDatabaseHas('farmer_profiles', [
            'id' => $profile->id,
            'approval_status' => FarmerProfile::STATUS_REJECTED,
            'reviewed_by' => $admin->id,
            'review_note' => 'Business details need correction.',
        ]);
    }

    public function test_rejection_and_suspension_require_a_review_note(): void
    {
        $admin = $this->createAdmin();
        $pending = $this->createFarmer();
        $approved = $this->createFarmer(FarmerProfile::STATUS_APPROVED, 'Approved Farm', 'Approved Farmer', 'approved@example.com');

        $this->actingAs($admin)
            ->patch(route('admin.farmers.approvals.update', $pending), [
                'approval_status' => FarmerProfile::STATUS_REJECTED,
            ])->assertSessionHasErrors('review_note');

        $this->actingAs($admin)
            ->patch(route('admin.farmers.approvals.update', $approved), [
                'approval_status' => FarmerProfile::STATUS_SUSPENDED,
            ])->assertSessionHasErrors('review_note');

        $this->assertSame(FarmerProfile::STATUS_PENDING, $pending->fresh()->approval_status);
        $this->assertSame(FarmerProfile::STATUS_APPROVED, $approved->fresh()->approval_status);
    }

    public function test_approved_farmer_can_be_suspended_and_reactivated(): void
    {
        $admin = $this->createAdmin();
        $profile = $this->createFarmer(FarmerProfile::STATUS_APPROVED);

        $this->actingAs($admin)
            ->patch(route('admin.farmers.approvals.update', $profile), [
                'approval_status' => FarmerProfile::STATUS_SUSPENDED,
                'review_note' => 'Selling access paused for review.',
            ])->assertRedirect();

        $this->assertSame(FarmerProfile::STATUS_SUSPENDED, $profile->fresh()->approval_status);

        $this->actingAs($admin)
            ->patch(route('admin.farmers.approvals.update', $profile), [
                'approval_status' => FarmerProfile::STATUS_APPROVED,
            ])->assertRedirect();

        $profile->refresh();
        $this->assertSame(FarmerProfile::STATUS_APPROVED, $profile->approval_status);
        $this->assertNull($profile->review_note);
    }

    public function test_rejected_farmer_can_return_to_pending_or_be_approved(): void
    {
        $admin = $this->createAdmin();
        $pendingAgain = $this->createFarmer(FarmerProfile::STATUS_REJECTED);
        $approved = $this->createFarmer(FarmerProfile::STATUS_REJECTED, 'Second Chance Farm', 'Second Farmer', 'second@example.com');

        $this->actingAs($admin)->patch(route('admin.farmers.approvals.update', $pendingAgain), [
            'approval_status' => FarmerProfile::STATUS_PENDING,
        ])->assertRedirect();
        $this->actingAs($admin)->patch(route('admin.farmers.approvals.update', $approved), [
            'approval_status' => FarmerProfile::STATUS_APPROVED,
        ])->assertRedirect();

        $this->assertSame(FarmerProfile::STATUS_PENDING, $pendingAgain->fresh()->approval_status);
        $this->assertSame(FarmerProfile::STATUS_APPROVED, $approved->fresh()->approval_status);
    }

    public function test_invalid_status_values_and_transitions_do_not_change_state(): void
    {
        $admin = $this->createAdmin();

        foreach (['ADMIN', 'banana'] as $index => $status) {
            $profile = $this->createFarmer(
                FarmerProfile::STATUS_PENDING,
                "Invalid Farm {$index}",
                "Invalid Farmer {$index}",
                "invalid-{$index}@example.com",
            );

            $this->actingAs($admin)
                ->patch(route('admin.farmers.approvals.update', $profile), [
                    'approval_status' => $status,
                ])->assertSessionHasErrors('approval_status');

            $this->assertSame(FarmerProfile::STATUS_PENDING, $profile->fresh()->approval_status);
        }

        $profile = $this->createFarmer(
            FarmerProfile::STATUS_PENDING,
            'Transition Farm',
            'Transition Farmer',
            'transition@example.com',
        );

        $this->actingAs($admin)
            ->patch(route('admin.farmers.approvals.update', $profile), [
                'approval_status' => FarmerProfile::STATUS_SUSPENDED,
                'review_note' => 'Attempt invalid transition.',
            ])->assertSessionHasErrors('approval_status');

        $this->assertSame(FarmerProfile::STATUS_PENDING, $profile->fresh()->approval_status);
    }

    public function test_request_cannot_spoof_the_reviewing_admin(): void
    {
        $admin = $this->createAdmin('active-admin@example.com');
        $otherAdmin = $this->createAdmin('other-admin@example.com');
        $profile = $this->createFarmer();

        $this->actingAs($admin)
            ->patch(route('admin.farmers.approvals.update', $profile), [
                'approval_status' => FarmerProfile::STATUS_APPROVED,
                'reviewed_by' => $otherAdmin->id,
            ])->assertRedirect();

        $this->assertSame($admin->id, $profile->fresh()->reviewed_by);
    }

    public function test_farmer_status_screen_immediately_reflects_admin_decisions(): void
    {
        $admin = $this->createAdmin();
        $profile = $this->createFarmer();

        $this->actingAs($admin)->patch(route('admin.farmers.approvals.update', $profile), [
            'approval_status' => FarmerProfile::STATUS_REJECTED,
            'review_note' => 'Registration needs revision.',
        ])->assertRedirect();

        $this->actingAs($profile->user)
            ->get(route('farmer.status'))
            ->assertOk()
            ->assertSee('NOT APPROVED')
            ->assertDontSee('Registration needs revision.');
    }

    private function createAdmin(string $email = 'admin@example.com'): User
    {
        return User::factory()->create([
            'role' => User::ROLE_ADMIN,
            'email' => $email,
        ]);
    }

    private function createFarmer(
        string $status = FarmerProfile::STATUS_PENDING,
        string $businessName = 'Green Valley Farm',
        string $name = 'Musa Ibrahim',
        string $email = 'farmer@example.com',
    ): FarmerProfile {
        $user = User::factory()->create([
            'role' => User::ROLE_FARMER,
            'name' => $name,
            'email' => $email,
            'phone' => '+234 802 345 6789',
        ]);

        return $user->farmerProfile()->create([
            'business_name' => $businessName,
            'address' => '24 Farm Road, Kaduna',
            'approval_status' => $status,
        ]);
    }
}

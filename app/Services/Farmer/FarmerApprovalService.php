<?php

namespace App\Services\Farmer;

use App\Models\FarmerProfile;
use App\Models\User;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class FarmerApprovalService
{
    /** @var array<string, list<string>> */
    private const ALLOWED_TRANSITIONS = [
        FarmerProfile::STATUS_PENDING => [
            FarmerProfile::STATUS_APPROVED,
            FarmerProfile::STATUS_REJECTED,
        ],
        FarmerProfile::STATUS_APPROVED => [
            FarmerProfile::STATUS_SUSPENDED,
        ],
        FarmerProfile::STATUS_SUSPENDED => [
            FarmerProfile::STATUS_APPROVED,
        ],
        FarmerProfile::STATUS_REJECTED => [
            FarmerProfile::STATUS_PENDING,
            FarmerProfile::STATUS_APPROVED,
        ],
    ];

    /** @return array<string, list<string>> */
    public function allowedTransitions(): array
    {
        return self::ALLOWED_TRANSITIONS;
    }

    /** @throws AuthorizationException */
    public function update(
        FarmerProfile $farmerProfile,
        User $admin,
        string $status,
        ?string $reviewNote,
    ): FarmerProfile {
        if ($admin->role !== User::ROLE_ADMIN) {
            throw new AuthorizationException('Only administrators can review farmer accounts.');
        }

        return DB::transaction(function () use ($farmerProfile, $admin, $status, $reviewNote): FarmerProfile {
            $profile = FarmerProfile::query()
                ->lockForUpdate()
                ->findOrFail($farmerProfile->getKey());

            if (! in_array($status, self::ALLOWED_TRANSITIONS[$profile->approval_status] ?? [], true)) {
                throw ValidationException::withMessages([
                    'approval_status' => "A farmer account cannot move from {$profile->approval_status} to {$status}.",
                ]);
            }

            $profile->update([
                'approval_status' => $status,
                'reviewed_by' => $admin->getKey(),
                'reviewed_at' => now(),
                'review_note' => filled($reviewNote) ? trim($reviewNote) : null,
            ]);

            return $profile->refresh();
        });
    }
}

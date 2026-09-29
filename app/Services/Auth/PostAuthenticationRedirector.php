<?php

namespace App\Services\Auth;

use App\Models\FarmerProfile;
use App\Models\User;

class PostAuthenticationRedirector
{
    public function routeName(User $user): string
    {
        if ($user->role !== User::ROLE_FARMER) {
            return 'marketplace.home';
        }

        return $user->farmerProfile?->approval_status === FarmerProfile::STATUS_APPROVED
            ? 'marketplace.home'
            : 'farmer.status';
    }
}

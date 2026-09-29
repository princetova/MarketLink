<?php

namespace App\Http\Controllers\Farmer;

use App\Http\Controllers\Controller;
use App\Models\FarmerProfile;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AccountStatusController extends Controller
{
    public function __invoke(Request $request): View
    {
        $farmerProfile = $request->user()->farmerProfile;

        $content = match ($farmerProfile?->approval_status) {
            FarmerProfile::STATUS_PENDING => [
                'tone' => 'pending',
                'badge' => 'PENDING APPROVAL',
                'heading' => 'Your farmer account is under review',
                'message' => 'Thanks for joining MarketLink. Your account has been created successfully and is waiting for approval before you can begin selling.',
                'panel_heading' => 'What happens after approval?',
                'panel_message' => 'Once approved, you will be able to manage products, inventory, pickup availability, and orders.',
                'action' => 'Browse MarketLink',
            ],
            FarmerProfile::STATUS_APPROVED => [
                'tone' => 'approved',
                'badge' => 'APPROVED',
                'heading' => 'Your farmer account is approved',
                'message' => 'Your MarketLink farmer account is ready.',
                'panel_heading' => 'Your account is active',
                'panel_message' => 'Farmer selling tools will be available here as MarketLink continues to grow.',
                'action' => 'Continue to MarketLink',
            ],
            FarmerProfile::STATUS_SUSPENDED => [
                'tone' => 'suspended',
                'badge' => 'SUSPENDED',
                'heading' => 'Your farmer account is suspended',
                'message' => 'Access to Farmer selling features is currently unavailable.',
                'panel_heading' => 'Account access',
                'panel_message' => 'You may continue browsing MarketLink. Contact MarketLink support if you need more information about your account.',
                'action' => 'Browse MarketLink',
            ],
            FarmerProfile::STATUS_REJECTED => [
                'tone' => 'rejected',
                'badge' => 'NOT APPROVED',
                'heading' => 'Your farmer registration was not approved',
                'message' => 'Your registration is not currently approved for Farmer selling features.',
                'panel_heading' => 'Need more information?',
                'panel_message' => 'You may continue browsing MarketLink or contact MarketLink for more information about your registration.',
                'action' => 'Browse MarketLink',
            ],
            null => [
                'tone' => 'unavailable',
                'badge' => 'SETUP INCOMPLETE',
                'heading' => 'Your farmer account setup is incomplete',
                'message' => 'We could not find the Farmer profile connected to this account.',
                'panel_heading' => 'Account assistance required',
                'panel_message' => 'Please contact MarketLink support so your account information can be reviewed safely.',
                'action' => 'Browse MarketLink',
            ],
            default => [
                'tone' => 'unavailable',
                'badge' => 'STATUS UNAVAILABLE',
                'heading' => 'We could not verify your farmer account status',
                'message' => 'Your Farmer profile contains an account status that MarketLink cannot currently verify.',
                'panel_heading' => 'Account assistance required',
                'panel_message' => 'Please contact MarketLink support before attempting to use Farmer selling features.',
                'action' => 'Browse MarketLink',
            ],
        };

        return view('farmer.status', [
            'farmerProfile' => $farmerProfile,
            ...$content,
        ]);
    }
}

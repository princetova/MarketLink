<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\UpdateFarmerApprovalRequest;
use App\Models\FarmerProfile;
use App\Services\Farmer\FarmerApprovalService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class FarmerApprovalController extends Controller
{
    public function index(Request $request, FarmerApprovalService $approvalService): View
    {
        $statuses = [
            FarmerProfile::STATUS_PENDING,
            FarmerProfile::STATUS_APPROVED,
            FarmerProfile::STATUS_SUSPENDED,
            FarmerProfile::STATUS_REJECTED,
        ];

        $filters = $request->validate([
            'search' => ['nullable', 'string', 'max:100'],
            'status' => ['nullable', 'string', Rule::in($statuses)],
        ]);

        $search = trim($filters['search'] ?? '');
        $status = $filters['status'] ?? null;

        $farmerProfiles = FarmerProfile::query()
            ->with('user')
            ->when($search !== '', function ($query) use ($search): void {
                $query->where(function ($query) use ($search): void {
                    $query->where('business_name', 'like', "%{$search}%")
                        ->orWhereHas('user', function ($query) use ($search): void {
                            $query->where('name', 'like', "%{$search}%")
                                ->orWhere('email', 'like', "%{$search}%");
                        });
                });
            })
            ->when($status, fn ($query) => $query->where('approval_status', $status))
            ->orderByRaw('CASE WHEN approval_status = ? THEN 0 ELSE 1 END', [FarmerProfile::STATUS_PENDING])
            ->latest()
            ->paginate(20)
            ->withQueryString();

        $statusCounts = FarmerProfile::query()
            ->selectRaw('approval_status, COUNT(*) as total')
            ->whereIn('approval_status', $statuses)
            ->groupBy('approval_status')
            ->pluck('total', 'approval_status');

        return view('admin.farmer-approvals.index', [
            'farmerProfiles' => $farmerProfiles,
            'statusCounts' => $statusCounts,
            'statuses' => $statuses,
            'allowedTransitions' => $approvalService->allowedTransitions(),
            'search' => $search,
            'activeStatus' => $status,
        ]);
    }

    public function update(
        UpdateFarmerApprovalRequest $request,
        FarmerProfile $farmerProfile,
        FarmerApprovalService $approvalService,
    ): RedirectResponse {
        $validated = $request->validated();

        $updatedProfile = $approvalService->update(
            $farmerProfile,
            $request->user(),
            $validated['approval_status'],
            $validated['review_note'] ?? null,
        );

        return back()->with(
            'status',
            "{$updatedProfile->business_name} is now {$updatedProfile->approval_status}.",
        );
    }
}

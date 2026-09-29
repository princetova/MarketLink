<?php

use App\Http\Controllers\Admin\FarmerApprovalController;
use App\Models\User;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'role:'.User::ROLE_ADMIN])->group(function (): void {
    Route::get('/farmers/approvals', [FarmerApprovalController::class, 'index'])
        ->name('farmers.approvals.index');
    Route::patch('/farmers/{farmerProfile}/approval', [FarmerApprovalController::class, 'update'])
        ->name('farmers.approvals.update');
});

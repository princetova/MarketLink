<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\RegisterFarmerRequest;
use App\Models\FarmerProfile;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;

class RegisteredFarmerController extends Controller
{
    public function create(): View
    {
        return view('auth.register-farmer');
    }

    public function store(RegisterFarmerRequest $request): RedirectResponse
    {
        $user = DB::transaction(function () use ($request): User {
            $user = User::create([
                'name' => $request->string('name')->toString(),
                'email' => $request->string('email')->toString(),
                'phone' => $request->string('phone')->toString(),
                'role' => User::ROLE_FARMER,
                'password' => Hash::make($request->string('password')->toString()),
            ]);

            $user->farmerProfile()->create([
                'business_name' => $request->string('business_name')->toString(),
                'address' => $request->string('address')->toString(),
                'approval_status' => FarmerProfile::STATUS_PENDING,
            ]);

            return $user;
        });

        Auth::login($user);
        $request->session()->regenerate();

        return redirect()
            ->route('marketplace.home')
            ->with('status', 'Your farmer account has been created and is pending approval.');
    }
}

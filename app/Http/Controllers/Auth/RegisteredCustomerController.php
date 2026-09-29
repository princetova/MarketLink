<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\RegisterCustomerRequest;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;

class RegisteredCustomerController extends Controller
{
    public function create(): View
    {
        return view('auth.register-customer');
    }

    public function store(RegisterCustomerRequest $request): RedirectResponse
    {
        $user = DB::transaction(function () use ($request): User {
            $user = User::create([
                'name' => $request->string('name')->toString(),
                'email' => $request->string('email')->toString(),
                'phone' => $request->string('phone')->toString(),
                'role' => User::ROLE_CUSTOMER,
                'password' => Hash::make($request->string('password')->toString()),
            ]);

            $user->customerProfile()->create([
                'address' => $request->string('address')->toString(),
            ]);

            return $user;
        });

        Auth::login($user);
        $request->session()->regenerate();

        return redirect()
            ->route('marketplace.home')
            ->with('status', 'Welcome to MarketLink. Your customer account is ready.');
    }
}

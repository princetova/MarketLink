<?php

namespace App\Http\Requests\Auth;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;

class RegisterCustomerRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /** @return array<string, array<int, mixed>> */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:120'],
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique(User::class)],
            'phone' => ['required', 'string', 'min:7', 'max:32', 'regex:/^[0-9+().\-\s]+$/'],
            'address' => ['required', 'string', 'max:500'],
            'password' => ['required', 'confirmed', Password::min(8)->mixedCase()->numbers()],
        ];
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'name' => trim((string) $this->input('name')),
            'email' => Str::lower(trim((string) $this->input('email'))),
            'phone' => trim((string) $this->input('phone')),
            'address' => trim((string) $this->input('address')),
        ]);
    }
}

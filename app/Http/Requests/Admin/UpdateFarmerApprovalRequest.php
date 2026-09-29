<?php

namespace App\Http\Requests\Admin;

use App\Models\FarmerProfile;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateFarmerApprovalRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /** @return array<string, mixed> */
    public function rules(): array
    {
        return [
            'approval_status' => [
                'required',
                'string',
                Rule::in([
                    FarmerProfile::STATUS_PENDING,
                    FarmerProfile::STATUS_APPROVED,
                    FarmerProfile::STATUS_SUSPENDED,
                    FarmerProfile::STATUS_REJECTED,
                ]),
            ],
            'review_note' => [
                Rule::requiredIf(fn (): bool => in_array($this->input('approval_status'), [
                    FarmerProfile::STATUS_SUSPENDED,
                    FarmerProfile::STATUS_REJECTED,
                ], true)),
                'nullable',
                'string',
                'max:500',
            ],
        ];
    }
}

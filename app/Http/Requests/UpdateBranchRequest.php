<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateBranchRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->hasRole('Super Admin');
    }

    public function rules(): array
    {
        $branchId = $this->route('branch')->id ?? $this->route('branch');

        return [
            'code' => ['required', 'string', 'max:20', Rule::unique('branches', 'code')->ignore($branchId)],
            'name' => ['required', 'string', 'max:150'],
            'address' => ['nullable', 'string', 'max:255'],
            'phone' => ['nullable', 'string', 'max:30'],
            'is_active' => ['boolean'],
        ];
    }
}
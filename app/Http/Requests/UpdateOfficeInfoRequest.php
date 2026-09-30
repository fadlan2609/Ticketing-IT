<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateOfficeInfoRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->hasRole('Super Admin');
    }

    public function rules(): array
    {
        return [
            'title' => ['required', 'string', 'max:255'],
            'summary' => ['nullable', 'string', 'max:500'],
            'content' => ['required', 'string', 'min:10'],
            'category' => ['required', 'in:announcement,policy,contact,hours,event,general'],
            'icon' => ['nullable', 'string', 'max:20'],
            'is_pinned' => ['boolean'],
            'is_published' => ['boolean'],
            'published_until' => ['nullable', 'date'],
        ];
    }
}
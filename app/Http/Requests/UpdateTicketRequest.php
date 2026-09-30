<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateTicketRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('ticket.edit');
    }

    public function rules(): array
    {
        return [
            'title' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string', 'min:10'],
            'category_id' => ['required', 'exists:categories,id'],
            'priority' => ['required', 'in:low,medium,high,critical'],
            'branch_id' => ['nullable', 'exists:branches,id'],
        ];
    }
}
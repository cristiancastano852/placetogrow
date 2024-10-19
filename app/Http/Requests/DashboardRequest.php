<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class DashboardRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'startDate' => 'nullable|date',
            'endDate' => 'nullable|date',
            'micrositeId' => 'nullable|integer|exists:microsites,id',
        ];
    }

    public function prepareForValidation()
    {
        $this->merge([
            'startDate' => $this->input('startDate', now()->subMonth()->startOfMonth()->format('Y-m-d')),
            'endDate' => $this->input('endDate', now()->subMonth()->endOfMonth()->format('Y-m-d')),
        ]);
    }
}

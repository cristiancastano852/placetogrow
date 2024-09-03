<?php

namespace App\Http\Requests;

use App\Constants\TimeUnitSubscription;
use Illuminate\Foundation\Http\FormRequest;

class StorePlanRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return True;
    }

    public function rules(): array
    {
        return [
            'plans.*.name' => 'required|string|max:255',
            'plans.*.price' => 'required|numeric|min:1',
            'plans.*.description' => 'nullable|string|max:1000',
            'plans.*.duration_unit' => 'required|string|in:' . implode(',', TimeUnitSubscription::toArray()),
            'plans.*.billing_frequency' => 'required|integer|min:1',
            'plans.*.duration_period' => 'required|integer|min:1',
        ];
    }
}

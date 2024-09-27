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
        return true;
    }

    public function rules(): array
    {
        return [
            'plan.name' => 'required|string|max:255',
            'plan.price' => 'required|numeric|min:1',
            'plan.description' => 'required|string|max:1000',
            'plan.duration_unit' => 'required|string|in:'.implode(',', TimeUnitSubscription::toArray()),
            'plan.billing_frequency' => 'required|integer|min:1',
            'plan.duration_period' => 'required|integer|min:1',
        ];
    }

    public function attributes(): array
    {
        return [
            'plan.name' => 'nombre del plan',
            'plan.price' => 'precio',
            'plan.description' => 'descripción',
            'plan.duration_unit' => 'unidad de duración',
            'plan.billing_frequency' => 'frecuencia del cobro',
            'plan.duration_period' => 'periodo de duración',
        ];
    }
}

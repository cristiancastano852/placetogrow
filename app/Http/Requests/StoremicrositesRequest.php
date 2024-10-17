<?php

namespace App\Http\Requests;

use App\Constants\Currency;
use App\Constants\DocumentTypes;
use App\Constants\MicrositesTypes;
use Illuminate\Foundation\Http\FormRequest;

class StoremicrositesRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'slug' => 'required|max:50|unique:microsites,slug',
            'name' => 'required|max:100',
            'category_id' => 'required|exists:categories,id',
            'document_type' => 'required|in:'.implode(',', array_column(DocumentTypes::cases(), 'name')),
            'document_number' => 'required|string|max:20',
            'logo' => 'required|string',
            'currency' => 'required|in:'.implode(',', array_column(Currency::cases(), 'name')),
            'site_type' => 'required|in:'.implode(',', array_column(MicrositesTypes::cases(), 'name')),
            'payment_expiration' => 'required|integer|min:1',
            'payment_retries' => 'nullable|integer|min:1',
            'retry_duration' => 'nullable|integer|min:1',
            'late_fee_percentage' => 'nullable|numeric|min:0|max:100',
            'payment_fields' => 'nullable|array',
            'payment_fields.*.label' => 'required|string|max:255',
            'payment_fields.*.name' => 'required|string|max:255',
            'payment_fields.*.type' => 'required|string|in:select,input',
            'payment_fields.*.optional' => 'required|boolean',
            'payment_fields.*.validation' => 'nullable|string|in:text,number,email',
            'payment_fields.*.placeholder' => 'nullable|string|max:255',

        ];
    }
}

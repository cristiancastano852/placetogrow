<?php

namespace App\Http\Requests;

use App\Constants\Currency;
use App\Constants\DocumentTypes;
use App\Constants\PaymentGateway;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StorePaymentRequest extends FormRequest
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
            // 'description' => ['required', 'string', 'min:3', 'max:100'],
            // 'amount' => ['required', 'integer', 'min:1', 'max:999999999999'],
            // 'microsite_id' => ['required', 'numeric', 'exists:microsites,id'],
            // 'currency' => ['required', Rule::in(Currency::toArray())],
            // 'name' => ['required', 'string', 'min:3', 'max:20'],
            // 'last_name' => ['required', 'string', 'min:3', 'max:20'],
            // 'email' => ['required', 'string', 'email'],
            // 'document_number' => ['required', 'numeric', 'digits_between:6,20'],
            // 'document_type' => ['required', Rule::in(DocumentTypes::toArray())],
            // 'gateway' => ['required', Rule::in(PaymentGateway::toArray())],
        ];
    }
}

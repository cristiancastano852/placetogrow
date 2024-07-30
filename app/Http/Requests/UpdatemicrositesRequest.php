<?php

namespace App\Http\Requests;

use App\Constants\Currency;
use App\Constants\DocumentTypes;
use App\Constants\MicrositesTypes;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdatemicrositesRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
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
            'slug' => [
                'required',
                'max:50',
                Rule::unique('microsites')->ignore($this->route('microsite')),
            ],
            'name' => 'required|max:100',
            'category_id' => 'required|exists:categories,id',
            'document_type' => 'required|in:'.implode(',', array_column(DocumentTypes::cases(), 'name')),
            'document_number' => 'required|string|max:20',
            'logo' => 'required|string',
            'currency' => 'required|in:'.implode(',', array_column(Currency::cases(), 'name')),
            'site_type' => 'required|in:'.implode(',', array_column(MicrositesTypes::cases(), 'name')),
            'payment_expiration' => 'required|integer|min:1',
        ];
    }
}

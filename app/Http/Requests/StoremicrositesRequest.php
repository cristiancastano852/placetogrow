<?php

namespace App\Http\Requests;

use App\Constants\Currency;
use App\Constants\DocumentTypes;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Log;
use PgSql\Lob;

class StoremicrositesRequest extends FormRequest
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
        Log::info('Validando datos del formulario:', $this->all());
        return [
            'slug' => 'required|max:50|unique:microsites',
            'name' => 'required|max:100',
            'document_type' => 'required',
            'document_number' => 'required',
            'category_id' => 'required|exists:categories,id',
        ];
    }
}

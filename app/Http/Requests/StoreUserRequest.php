<?php

namespace App\Http\Requests;

use App\Constants\PermissionSlug;
use App\Constants\PolicyName;
use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules;

class StoreUserRequest extends FormRequest
{
    public function authorize(): bool
    {

        return $this->user()->can(PolicyName::CREATE, User::class);

    }

    public function rules(): array
    {

        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'role' => ['required'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ];
    }
}

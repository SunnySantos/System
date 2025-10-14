<?php

namespace App\Http\Requests\Role;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class UpdateUserRoleAfterDeleteRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return Auth::check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'role_id'   => ['required', 'exists:roles,id'],
            'new_role_id'  => ['required', 'exists:roles,id', 'different:role_id'],
        ];
    }


    public function messages(): array
    {
        return [
            'new_role_id.different' => 'The new role must be different from the current role.',
        ];
    }
}

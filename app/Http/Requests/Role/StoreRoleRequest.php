<?php

namespace App\Http\Requests\Role;

use App\Models\Role;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class StoreRoleRequest extends FormRequest
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
        // Cache the authenticated routes for 1 hour to avoid re-processing
        $authenticatedRoutes = Role::getAuthenticatedRoutes();

        return [
            'name' => ['required', 'string', 'max:255', 'unique:roles,name'],
            'role_accesses' => ['nullable', 'array'],
            'role_accesses.*' => ['string', 'max:255', 'in:' . implode(',', $authenticatedRoutes)],
        ];
    }
}

<?php

namespace App\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreUserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true; // Temporary value. Make it false after login feature is implemented
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'first_name'        => ['required', 'string', 'max:255'],
            'middle_name'       => ['nullable', 'string', 'max:255'],
            'last_name'         => ['required', 'string', 'max:255'],
            'phone'             => ['nullable', 'string', 'min:5', 'max:20', 'regex:/^[0-9*#+()\- ]+$/'],
            'street_address'    => ['required', 'string', 'max:255'],
            'country'           => [
                'required',
                'int',
                Rule::exists('countries', 'id'),
            ],
            'city'              => [
                'required',
                'int',
                Rule::exists('cities', 'id')->where(function ($query) {
                    $query->where('state_id', $this->input('state'));
                }),
            ],
            'state'             => [
                'required',
                'int',
                Rule::exists('states', 'id')->where(function ($query) {
                    $query->where('country_id', $this->input('country'));
                }),
            ],
            'zip'               => ['required', 'string', 'max:20'],
            'email'             => ['required', 'email', 'unique:users,email'],
            'password'          => ['required', 'string', 'min:8', 'confirmed'],
        ];
    }

    public function messages(): array
    {
        return [
            'country.required'  => 'Please select a country.',
            'country.exists'    => 'The selected country does not exist.',

            'state.required'    => 'Please select a state.',
            'state.exists'      => 'The selected state is invalid or does not belong to the chosen country.',

            'city.required'     => 'Please select a city.',
            'city.exists'       => 'The selected city is invalid or does not belong to the chosen state.',
        ];
    }
}

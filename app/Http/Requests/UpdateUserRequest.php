<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules;
use Illuminate\Validation\Rule;

class UpdateUserRequest extends FormRequest
{
    public function authorize()
    {
        return auth()->user()->hasRole('admin');
    }

    public function rules()
    {
        return [
            'name' => 'required|string|max:255',
            'fullname' => 'sometimes|nullable|string|max:255',
            'phone' => 'sometimes|nullable|string|max:255',
            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                Rule::unique('users')->ignore($this->user->id)
            ],
            'password' => ['sometimes','nullable', Rules\Password::defaults()],
            'role' => 'string|exists:roles,name',
            'is_active' => 'bool',
            'is_stopped' => 'bool',
            'balance' => 'numeric',
            'birthdate' => 'sometimes|nullable||date',
            'documents' => 'sometimes|nullable|array',
            'documents.inn' => 'sometimes|nullable|string|max:20',
            'documents.pasport_birthplace' => 'sometimes|nullable|string|max:255',
            'documents.pasport_series' => 'sometimes|nullable|string|max:10',
            'documents.pasport_number' => 'sometimes|nullable|string|max:20',
            'documents.pasport_who' => 'sometimes|nullable|string|max:255',
            'documents.pasport_when' => 'sometimes|nullable|date',
            'documents.pasport_code' => 'sometimes|nullable|string|max:10',
            'payment_method' => 'sometimes|nullable|array',
            'payment_method.bank_name' => 'sometimes|nullable|string|max:20',
            'payment_method.bank_bik' => 'sometimes|nullable|string|min:5',
            'payment_method.bank_rs' => 'sometimes|nullable|string|min:10'
        ];
    }
}
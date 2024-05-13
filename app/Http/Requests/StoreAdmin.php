<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class StoreAdmin extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return (Auth::guard('admin')->check());
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            // 'email' => ['string', 'email', 'max:255', 'unique:admins'],
            // 'password' => ['required', 'string', 'min:6'],
            // 'password' => ['required', 'string', 'min:6', 'confirmed'],
        ];
    }
    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array
     */
    public function messages()
    {
        return [
            'name.required' => 'A name is required',
            'email.required'  => 'A email is required',
        ];
    }
}

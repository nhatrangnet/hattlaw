<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class UpdateAdmin extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return Auth::guard('admin')->check();
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
            // 'email' => ['string', 'email', 'max:255'],
            'avatar' => ['image','max:5000','mimes:jpeg,jpg,bmp,png,gif'],
            'image' => ['image','max:5000','mimes:jpeg,jpg,bmp,png,gif'],
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
            'name.required' => trans('form.name.required'),
            'email.required'  => trans('form.email.required'),
        ];
    }
}

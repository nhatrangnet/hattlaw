<?php

namespace App\Http\Requests\Blog;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class StoreCategory extends FormRequest
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
            'name' => ['required', 'string', 'max:255','unique:blog_categories,name,'.mahoa_giaima('giaima',$this->request->get('id'))],
            'description' => ['required', 'string', 'min:3'],
        ];
    }
    public function messages()
    {
        return [
            'name.required' => trans('form.name.required'),
            'name.unique' => trans('form.name.unique'),
            'description.required'  => trans('form.description.required'),
        ];
    }
}

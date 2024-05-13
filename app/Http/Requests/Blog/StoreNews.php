<?php

namespace App\Http\Requests\Blog;
use Illuminate\Support\Facades\Auth;

use Illuminate\Foundation\Http\FormRequest;

class StoreNews extends FormRequest
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
            'title' => ['required', 'string', 'max:255','unique:blog_news,title,'.mahoa_giaima('giaima',$this->request->get('id'))],
            'summary' => ['required', 'string', 'min:3'],
            'content' => ['required', 'string', 'min:3'],
        ];
    }
    public function messages()
    {
        return [
            'title.required' => trans('form.name.required'),
            'title.unique' => trans('form.name.unique'),
            'content.required'  => trans('form.description.required'),
        ];
    }
}

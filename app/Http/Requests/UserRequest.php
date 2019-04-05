<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'name' => 'required|between:3,25|unique:users,name,' . \Auth::id(),
            'email' => 'required|email',
            'elearning_id' => 'nullable|between:8,8',
            'elearning_pwd' => 'nullable|min:3',
        ];
    }
}

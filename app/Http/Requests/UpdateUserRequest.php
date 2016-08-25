<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;

class UpdateUserRequest extends Request
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $parts = explode("/", UpdateUserRequest::path());
        $userId = end($parts);
        return [
            'name' => "max:255",
            'email' => "required|email|max:255|unique:users,email,{$userId},id",
            'password' => "required|min:6",
            'image' => "mimes:jpg,jpeg,png,gif|max:2000",
        ];
    }
}

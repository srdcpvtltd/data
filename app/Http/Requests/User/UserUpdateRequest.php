<?php

namespace App\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;
use Hash;

class UserUpdateRequest extends FormRequest
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
        $rules['first_name'] = 'string|max:255';
        $rules['last_name'] = 'string|max:255';
        $rules['email'] = 'required|string|email|max:255|unique:users,email,'.$this->route()->parameter('user');

        if ($this->input('password')) {
            $rules['password'] = 'required|string|min:6';
            $this->request->set('password', Hash::make($this->input('password')));
        } else {
            $this->request->remove('password');
        }
        
        return $rules;
    }
}

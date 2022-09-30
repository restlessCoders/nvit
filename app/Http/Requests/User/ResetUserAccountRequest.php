<?php

namespace App\Http\Requests\User;
use Illuminate\Http\Request;
use Illuminate\Foundation\Http\FormRequest;

class ResetUserAccountRequest extends FormRequest
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
        $id = encryptor('decrypt',Request::instance()->id);
        return [
            'username'  =>  "required|unique:users,username, $id",
            'name'  =>  'required',
            'mobileNumber'  =>  "required|unique:users,mobileNumber, $id",
            'email'  =>  "required|unique:users,email, $id",
        ];
    }

    public function messages()
    {
        return [
            'required' => 'The :attribute field is required.',
        ];
    }
}

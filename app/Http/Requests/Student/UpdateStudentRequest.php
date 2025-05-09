<?php

namespace App\Http\Requests\Student;

use Illuminate\Http\Request;
use Illuminate\Foundation\Http\FormRequest;

class UpdateStudentRequest extends FormRequest
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
        $id = encryptor('decrypt', Request::instance()->id);

        // Initialize the rules array
        $rules = [
            'name' => 'required|string',
            'contact' => "required|regex:/^01[34-9]\d{8}$/|unique:students,contact,$id",
            'altContact' => "nullable|regex:/^01[34-9]\d{8}$/|unique:students,altContact,$id",
            'email' => "nullable|string|unique:students,email,$id",
        ];

        // Check condition to apply proper rules
        if (strtolower(currentUser()) === 'superadmin' || strtolower(currentUser()) === 'salesmanager' || strtolower(currentUser()) === 'operationmanager') {
            $rules['refId'] = 'required';
        }

        return $rules;
    }

    /**
     * Get the custom validation messages for the request.
     *
     * @return array
     */
    public function messages()
    {
        return [
            'required' => 'The :attribute field is required.',
        ];
    }
}

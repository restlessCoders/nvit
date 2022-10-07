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
        $id = encryptor('decrypt',Request::instance()->id);
        // Check condition to apply proper rules
        if (strtolower(currentUser()) === 'superadmin' || strtolower(currentUser()) === 'salesmanager' ||  strtolower(currentUser()) === 'operationmanager') {
            $rules['executiveId'] = 'required';
            $rules['refId'] 	  = 'required';
        }
        $rules = [
            'name' 		    => 'required|string',
            'contact'       => "required|string|unique:students,contact,$id",
            'email'         => "string|unique:students,email,$id",
        ];
        return $rules;
    }

    public function messages()
    {
        return [
            'required' => 'The :attribute field is required.',
        ];
    }
}

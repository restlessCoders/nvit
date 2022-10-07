<?php

namespace App\Http\Requests\Student;

use Illuminate\Foundation\Http\FormRequest;

class NewStudentRequest extends FormRequest
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
        return [
            'name' 		    => 'required|string',
            'contact'       => 'required|regex:/^(?:\+?88)?01[35-9]\d{8}$/|unique:students',
            'email'         => 'string|unique:students,email',
            'executiveId' 	=> 'required',
            'refId' 	    => 'required',
        ];
    }

    public function messages()
    {
        return [
            'required' => 'This field is required.'
        ];
    }
}

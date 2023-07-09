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
        if (strtolower(currentUser()) === 'superadmin' || strtolower(currentUser()) === 'salesmanager' ||  strtolower(currentUser()) === 'operationmanager' ||  strtolower(currentUser()) === 'frontdesk') {
            $rules['executiveId'] = 'required';
        }
        return [
            'name' 		    => 'required|string',
            'contact'       => 'required|regex:/^(?:\+?88)?01[34-9]\d{8}$/|unique:students',
            'altContact'    => 'nullable|regex:/^(?:\+?88)?01[34-9]\d{8}$/|unique:students',
            'email'         => 'nullable|string|unique:students,email',
            'refId' 	    => 'required',
            'executiveId' 	    => isset($rules['executiveId']) ? $rules['executiveId'] : 'nullable',
        ];
    }

    public function messages()
    {
        return [
            'required' => 'This field is required.'
        ];
    }
}

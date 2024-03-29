<?php

namespace App\Http\Requests\Student;
use Illuminate\Http\Request;
use Illuminate\Foundation\Http\FormRequest;

class StudentCourseRequest extends FormRequest
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
            /*'batch_id' 		    => 'required',
            'status'            => 'required',*/
        ];
    }

    public function messages()
    {
        return [
            'required' => 'The :attribute field is required.',
        ];
    }
}

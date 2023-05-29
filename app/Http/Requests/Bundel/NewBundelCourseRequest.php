<?php

namespace App\Http\Requests\Course;

use Illuminate\Foundation\Http\FormRequest;

class NewBundelCourseRequest extends FormRequest
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
            'courseName'         => 'required',
            'rPrice'             => 'required',
        ];
    }

    public function messages()
    {
        return [
            'required' => 'This field is required.'
        ];
    }
}

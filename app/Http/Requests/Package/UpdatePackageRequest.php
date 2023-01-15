<?php

namespace App\Http\Requests\Package;
use Illuminate\Http\Request;
use Illuminate\Foundation\Http\FormRequest;

class UpdatePackageRequest extends FormRequest
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
            'pName'           => 'required',
            'price'           => 'required',
            'courseId'        => 'required',
            'startDate'       => 'required',
            'endDate'         => 'required',
            'endTime'         => 'required',
        ];
    }

    public function messages()
    {
        return [
            'required' => 'The :attribute field is required.',
        ];
    }
}

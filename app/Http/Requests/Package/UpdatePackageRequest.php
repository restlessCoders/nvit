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
            'packageType'     => 'required',
            'batchId'         => 'required_if:packageType,1',
            'courseId'        => 'required_if:packageType,1',
            'price'           => 'required_if:packageType,1',
            'iPrice'          => 'required_if:packageType,1',
            'startDate'       => 'required',
            'endDate'         => 'required',
            'dis'             => 'required_if:packageType,2'
        ];
    }

    public function messages()
    {
        return [
            'required' => 'This field is required.',
            'packageType.required' =>  'Package Type is mandatory Field',
            'batchId.required_if' =>  'Batch is mandatory Field',
            'courseId.required_if' =>  'Course is mandatory Field',
            'dis.required_if' =>  'Discount is mandatory Field',
        ];
    }
}

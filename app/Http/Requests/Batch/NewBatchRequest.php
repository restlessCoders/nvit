<?php

namespace App\Http\Requests\Batch;

use Illuminate\Foundation\Http\FormRequest;

class NewBatchRequest extends FormRequest
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
            'batchId'  =>'required|unique:batches,batchId',
            'courseId' 		    => 'required',
            /*'price'             => 'required',
            'discount'          => 'required',*/
            'status' 	        => 'required',
        ];
    }

    public function messages()
    {
        return [
            'required' => 'This field is required.',
            'unique' => "The :attribute already used. Please try another",
        ];
    }
}

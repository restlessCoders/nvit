<?php

namespace App\Http\Requests\Batch;
use Illuminate\Http\Request;
use Illuminate\Foundation\Http\FormRequest;

class UpdateBatchRequest extends FormRequest
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
    public function rules(Request $r)
    {
        $id=encryptor('decrypt',$r->uptoken);
        return [
            'batchId'  =>'required|unique:batches,batchId,'.$id,
            'courseId' 		    => 'required',
            /*'price'             => 'required',
            'discount'          => 'required',*/
            'status' 	        => 'required',
        ];
    }

    public function messages()
    {
        return [
            'required' => 'The :attribute field is required.',
            'unique' => "The :attribute already used. Please try another",
        ];
    }
}

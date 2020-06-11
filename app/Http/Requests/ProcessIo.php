<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProcessIo extends FormRequest
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
            //
            'dsp_budget.*.host_links' => 'required',
//            'dsp_budget.*.io_file' => 'required',
            'dsp_budget.*.dds_code' => 'required',
        ];
    }
}

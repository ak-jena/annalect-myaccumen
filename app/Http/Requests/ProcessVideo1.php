<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProcessVideo1 extends FormRequest
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
            'video_campaign_objective' => 'required',
            'video_primary_campaign_metric' => 'required',
            'video_primary_metric_value' => 'required'
        ];
    }

    /**
     * Specify custom error message
     *
     * @return array
     */
    public function messages()
    {
        return [
            //
            'video_primary_campaign_metric.required' => 'The primary campaign metric field is required.'
        ];
    }


}

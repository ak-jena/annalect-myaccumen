<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProcessCampaignInfo2 extends FormRequest
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
            'audio_budget' => 'sometimes|required|numeric|min:1000',
            'display_budget' => 'sometimes|required|numeric|min:1000',
            'rich_media_budget' => 'sometimes|required|numeric|min:1000',
            'mobile_budget' => 'sometimes|required|numeric|min:1000',
            'vod_budget' => 'sometimes|required|numeric|min:1000',
            'total_budget' => 'required|numeric',
            'background' => 'required',
            'target_audience_profile' => 'required',
            'vod_dsp.1' => 'required_without_all:vod_dsp.2,vod_dsp.3,vod_dsp.4,vod_dsp.5,vod_dsp.6,vod_dsp.12|sometimes|numeric|between:0,999999.99',
            'vod_dsp.2' => 'required_without_all:vod_dsp.1,vod_dsp.3,vod_dsp.4,vod_dsp.5,vod_dsp.6,vod_dsp.12|sometimes|numeric|between:0,999999.99',
            'vod_dsp.3' => 'required_without_all:vod_dsp.2,v.1,vod_dsp.4,vod_dsp.5,vod_dsp.6,vod_dsp.12|sometimes|numeric|between:0,999999.99',
            'vod_dsp.4' => 'required_without_all:vod_dsp.2,vod_dsp.3,vod_dsp.1,vod_dsp.5,vod_dsp.6,vod_dsp.12|sometimes|numeric|between:0,999999.99',
            'vod_dsp.5' => 'required_without_all:vod_dsp.2,vod_dsp.3,vod_dsp.4,vod_dsp.1,vod_dsp.6,vod_dsp.12|sometimes|numeric|between:0,999999.99',
            'vod_dsp.6' => 'required_without_all:vod_dsp.2,vod_dsp.3,vod_dsp.4,vod_dsp.5,vod_dsp.1,vod_dsp.12|sometimes|numeric|between:0,999999.99',
            'vod_dsp.12' => 'required_without_all:vod_dsp.2,vod_dsp.3,vod_dsp.4,vod_dsp.5,vod_dsp.1,vod_dsp.6|sometimes|numeric|between:0,999999.99'
        ];
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function messages()
    {
        return [
            //
            'total_budget.required' => 'Please enter a budget value for the relevant product(s).',
            'vod_dsp.1.required_without_all' => 'Please enter a budget for at least one DSP.',
            'vod_dsp.2.required_without_all' => 'Please enter a budget for at least one DSP.',
            'vod_dsp.3.required_without_all' => 'Please enter a budget for at least one DSP.',
            'vod_dsp.4.required_without_all' => 'Please enter a budget for at least one DSP.',
            'vod_dsp.5.required_without_all' => 'Please enter a budget for at least one DSP.',
            'vod_dsp.6.required_without_all' => 'Please enter a budget for at least one DSP.',
            'vod_dsp.12.required_without_all' => 'Please enter a budget for at least one DSP.'
        ];
    }
}

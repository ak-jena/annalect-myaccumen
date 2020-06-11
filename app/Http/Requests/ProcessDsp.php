<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProcessDsp extends FormRequest
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
            'dsp_data.display.4' => 'required_without_all:dsp_data.display.5,dsp_data.display.8,dsp_data.display.9,dsp_data.display.12|sometimes|numeric|between:1,999999.99',
            'dsp_data.display.5' => 'required_without_all:dsp_data.display.4,dsp_data.display.8,dsp_data.display.9,dsp_data.display.12|sometimes|numeric|between:1,999999.99',
            'dsp_data.display.8' => 'required_without_all:dsp_data.display.5,dsp_data.display.4,dsp_data.display.9,dsp_data.display.12|sometimes|numeric|between:1,999999.99',
            'dsp_data.display.9' => 'required_without_all:dsp_data.display.5,dsp_data.display.8,dsp_data.display.4,dsp_data.display.12|sometimes|numeric|between:1,999999.99',
            'dsp_data.display.12' => 'required_without_all:dsp_data.display.5,dsp_data.display.8,dsp_data.display.4,dsp_data.display.9|sometimes|numeric|between:1,999999.99',

            'dsp_data.mobile.5' => 'required_without_all:dsp_data.mobile.8,dsp_data.mobile.10,dsp_data.mobile.11,dsp_data.mobile.12,dsp_data.mobile.9|sometimes|numeric|between:1,999999.99',
            'dsp_data.mobile.8' => 'required_without_all:dsp_data.mobile.5,dsp_data.mobile.10,dsp_data.mobile.11,dsp_data.mobile.12,dsp_data.mobile.9|sometimes|numeric|between:1,999999.99',
            'dsp_data.mobile.10' => 'required_without_all:dsp_data.mobile.8,dsp_data.mobile.5,dsp_data.mobile.11,dsp_data.mobile.12,dsp_data.mobile.9|sometimes|numeric|between:1,999999.99',
            'dsp_data.mobile.11' => 'required_without_all:dsp_data.mobile.8,dsp_data.mobile.10,dsp_data.mobile.5,dsp_data.mobile.12,dsp_data.mobile.9|sometimes|numeric|between:1,999999.99',
            'dsp_data.mobile.12' => 'required_without_all:dsp_data.mobile.8,dsp_data.mobile.10,dsp_data.mobile.5,dsp_data.mobile.11,dsp_data.mobile.9|sometimes|numeric|between:1,999999.99',
            'dsp_data.mobile.9' => 'required_without_all:dsp_data.mobile.8,dsp_data.mobile.10,dsp_data.mobile.5,dsp_data.mobile.11,dsp_data.mobile.12|sometimes|numeric|between:1,999999.99',

            'dsp_data.rich_media.5' => 'required_without_all:dsp_data.rich_media.8,dsp_data.rich_media.9|sometimes|numeric|between:1,999999.99',
            'dsp_data.rich_media.8' => 'required_without_all:dsp_data.rich_media.5,dsp_data.rich_media.9,|sometimes|numeric|between:1,999999.99',
            'dsp_data.rich_media.9' => 'required_without_all:dsp_data.rich_media.5,dsp_data.rich_media.8|sometimes|numeric|between:1,999999.99',

            'dsp_data.audio.7' => 'required_without_all:dsp_data.audio.5,dsp_data.audio.8|sometimes|numeric|between:1,999999.99',

            'dsp_data.vod.1' => 'required_without_all:dsp_data.vod.2,dsp_data.vod.3,dsp_data.vod.4,dsp_data.vod.5,dsp_data.vod.6,dsp_data.vod.12|sometimes|numeric|between:1,999999.99',
            'dsp_data.vod.2' => 'required_without_all:dsp_data.vod.1,dsp_data.vod.3,dsp_data.vod.4,dsp_data.vod.5,dsp_data.vod.6,dsp_data.vod.12|sometimes|numeric|between:1,999999.99',
            'dsp_data.vod.3' => 'required_without_all:dsp_data.vod.2,dsp_data.vod.1,dsp_data.vod.4,dsp_data.vod.5,dsp_data.vod.6,dsp_data.vod.12|sometimes|numeric|between:1,999999.99',
            'dsp_data.vod.4' => 'required_without_all:dsp_data.vod.2,dsp_data.vod.3,dsp_data.vod.1,dsp_data.vod.5,dsp_data.vod.6,dsp_data.vod.12|sometimes|numeric|between:1,999999.99',
            'dsp_data.vod.5' => 'required_without_all:dsp_data.vod.2,dsp_data.vod.3,dsp_data.vod.4,dsp_data.vod.1,dsp_data.vod.6,dsp_data.vod.12|sometimes|numeric|between:1,999999.99',
            'dsp_data.vod.6' => 'required_without_all:dsp_data.vod.2,dsp_data.vod.3,dsp_data.vod.4,dsp_data.vod.5,dsp_data.vod.1,dsp_data.vod.12|sometimes|numeric|between:1,999999.99',
            'dsp_data.vod.12' => 'required_without_all:dsp_data.vod.1,dsp_data.vod.2,dsp_data.vod.3,dsp_data.vod.4,dsp_data.vod.5,dsp_data.vod.6|sometimes|numeric|between:1,999999.99'
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
            'dsp_data.display.4.required_without_all' => 'Please enter a budget for at least one DSP in Display.',
            'dsp_data.display.5.required_without_all' => 'Please enter a budget for at least one DSP in Display.',
            'dsp_data.display.8.required_without_all' => 'Please enter a budget for at least one DSP in Display.',
            'dsp_data.display.9.required_without_all' => 'Please enter a budget for at least one DSP in Display.',
            'dsp_data.display.12.required_without_all' => 'Please enter a budget for at least one DSP in Display.',

            'dsp_data.mobile.5.required_without_all' => 'Please enter a budget for at least one DSP in Mobile.',
            'dsp_data.mobile.8.required_without_all' => 'Please enter a budget for at least one DSP in Mobile.',
            'dsp_data.mobile.10.required_without_all' => 'Please enter a budget for at least one DSP in Mobile.',
            'dsp_data.mobile.11.required_without_all' => 'Please enter a budget for at least one DSP in Mobile.',
            'dsp_data.mobile.12.required_without_all' => 'Please enter a budget for at least one DSP in Mobile.',
            'dsp_data.mobile.9.required_without_all' => 'Please enter a budget for at least one DSP in Mobile.',

            'dsp_data.rich_media.5.required_without_all' => 'Please enter a budget for at least one DSP in Rich Media.',
            'dsp_data.rich_media.8.required_without_all' => 'Please enter a budget for at least one DSP in Rich Media.',
            'dsp_data.rich_media.9.required_without_all' => 'Please enter a budget for at least one DSP in Rich Media.',

            'dsp_data.audio.7.required_without_all' => 'Please enter a budget for at least one DSP in Audio.',

            'dsp_data.vod.1.required_without_all' => 'Please enter a budget for at least one DSP in VOD.',
            'dsp_data.vod.2.required_without_all' => 'Please enter a budget for at least one DSP in VOD.',
            'dsp_data.vod.3.required_without_all' => 'Please enter a budget for at least one DSP in VOD.',
            'dsp_data.vod.4.required_without_all' => 'Please enter a budget for at least one DSP in VOD.',
            'dsp_data.vod.5.required_without_all' => 'Please enter a budget for at least one DSP in VOD.',
            'dsp_data.vod.6.required_without_all' => 'Please enter a budget for at least one DSP in VOD.',
            'dsp_data.vod.12.required_without_all' => 'Please enter a budget for at least one DSP in VOD.'
        ];
    }
}

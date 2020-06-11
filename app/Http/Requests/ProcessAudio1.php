<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProcessAudio1 extends FormRequest
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
            'audio_campaign_objective' => 'required',
            'audio_primary_campaign_metric' => 'required',
            'audio_metric_goal_value' => 'required',
            'audio_geo_targeting' => 'required'
        ];
    }
}

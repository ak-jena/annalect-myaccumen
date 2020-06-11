<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProcessVideo2 extends FormRequest
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
            'video_inventory_screen_types' => 'required',
            'video_geo_targeting' => 'required',
            'video_creative_lengths' => 'required',
            'video_creative_types' => 'required',
        ];
    }
}

<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProcessBooking extends FormRequest
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
            'targeting_requirements'                => 'sometimes|required',
            'tracking_pixel_details' => 'sometimes|required_with:requested_tracking_pixels',
            'tracking_pixel_events' => 'sometimes|required_with:requested_tracking_pixels',
            'supplied_creative_formats.0.dimension' => 'sometimes|required',
            'data_collection_code'                  => 'sometimes|required',
            'adserver_metric'                       => 'sometimes|required',


        ];
    }

    public function messages()
    {
        return [
            //
            'supplied_creative_formats.0.dimension.required' => 'A supplied creative format is required.'
        ];
    }
}

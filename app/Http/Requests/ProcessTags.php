<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProcessTags extends FormRequest
{
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
            'creative_tag.1' => 'sometimes',
            'creative_tag.2' => 'sometimes',
            'creative_tag.3' => 'sometimes',
            'creative_tag.4' => 'sometimes'
        ];
    }
}

<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProcessGrid extends FormRequest
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
            'targeting_grid.1' => 'sometimes',
            'targeting_grid.2' => 'sometimes',
            'targeting_grid.3' => 'sometimes',
            'targeting_grid.4' => 'sometimes'
        ];
    }
}

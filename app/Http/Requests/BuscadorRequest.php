<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class BuscadorRequest extends FormRequest
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
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'search' => 'required',
            'page' => 'integer',
            'order' => Rule::in(['price_asc', 'price_desc', 'name_asc', 'name_desc']),
            'convenio' => 'string'
        ];
    }

    public function messages()
    {
        return [
            'search.required' => "Debe ingresar una ID o nombre de un producto."
        ];
    }
}

<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class FavoriteRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'quote' => 'required|unique:favorites|max:250',
        ];
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array
     */
    public function messages()
    {
        return [
            'name.required' => 'La cita de Kanye West es requerida.',
            'name.unique' => 'La cita de Kanye West ya ha sido registrada ingrese otro.',
            'name.max' => 'La cita de Kanye West debe ser maximo de 250 caracteres.',
        ];
    }
}

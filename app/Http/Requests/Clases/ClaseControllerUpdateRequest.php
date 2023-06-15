<?php

namespace App\Http\Requests\Clases;

use Illuminate\Foundation\Http\FormRequest;

class ClaseControllerUpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return request()->user()->isAdmin();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'coach_id' => 'required',
            'quota'       => 'required',
        ];
    }

    /**
     *  Name of the fields that will be translated.
     *
     *  @return  array
     */
    public function attributes()
    {
        return [
            'coach_id' => 'encargado de la clase',
            'quota' => 'cantidad máxima de alumnos',
        ];
    }
}

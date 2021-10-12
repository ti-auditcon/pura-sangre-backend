<?php

namespace App\Http\Requests\Settings;

use Illuminate\Validation\Rule;
use App\Models\Settings\Setting;
use Illuminate\Foundation\Http\FormRequest;

class SettingRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return auth()->user()->isAdmin();
    }

    /**
     *  Get the validation rules that apply to the request.
     *
     *  @return  array
     */
    public function rules()
    {
        return [
            // 'box_country'                     => 'required',
            // 'box_image'                       => 'mimes:jpeg,jpg,png',
            "minutes_to_send_notifications" => Rule::in(Setting::listOfAvailableMinutesToSendPushes()),
            "minutes_to_remove_users"       => [Rule::in(Setting::listOfAvailableMinutesToRemoveUsersFromClases()), 'lt:minutes_to_send_notifications'],
        ];
    }

    /**
     *  [attributes description]
     *
     *  @return  array
     */
    public function attributes(): array
    {
        return [
            'box_image'      => 'logo del centro deportivo',
            'calendar_start' => 'hora de inicio',
            'box_country'    => 'País',
        ];
    }

    /**
     * [messages description]
     *
     * @return  [type]  [return description]
     */
    public function messages()
    {
        return [
            "minutes_for_confirmation_clases.in" => 'Los minutos para la confirmación de clases debe ser uno de los tiempos definidos en la lista',
            "minutes_to_remove_users.in"         => 'Los minutos para remover a los alumnos debe ser uno de los tiempos definidos en la lista',
            'minutes_to_remove_users.lt'         => 'Los minutos para remover a los alumnos debe ser un número menor a el envío de la confirmación de la clase.',
        ];
    }
}

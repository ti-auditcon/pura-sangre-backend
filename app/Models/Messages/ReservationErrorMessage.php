<?php

namespace App\Models\Messages;

class ReservationErrorMessage
{
    /**
     * @var  string
     */
    const CLASS_ALREADY_BOOKED = 'Ya tienes una reserva en esta clase.';

    /**
     * @var  string
     */
    const CLASS_TYPE_LIMIT = 'No puede tener dos reservaciones del mismo tipo de clase en un mismo día.';

    /**
     * @var  string
     */
    const DAILY_LIMIT_RESERVATIONS = 'Ha alcanzado el máximo de reservas diarias de tu plan.';

    /**
     * @var  string
     */
    const USER_PLAN_WITHOUT_QUOTAS = 'No tienes mas cupos en tu plan para tomar esta clase.';

    /**
     * @var  string
     */
    const USER_WITHOUT_ACTIVE_PLAN = 'No tienes un plan activo o precompra que te permita reservar esta clase.';

    /**
     * @var  string
     */
    const PLAN_DISABLED_FOR_BLOCK = 'Tu plan no puede tomar clases en este horario para este tipo de clase.';
}

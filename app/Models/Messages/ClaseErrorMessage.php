<?php

namespace App\Models\Messages;

class ClaseErrorMessage
{
    /**
     *  @var  string
     */
    const DISABLED = 'Esta clase todavía no puede ser tomada, pero pronto se podrá.';

    /**
     *  @var  string
     */
    const HAS_ENDED = 'La clase ya no puede ser tomada por que la fecha y hora de inicio ya pasaron.';

    /**
     *  @var  string
     */
    const IS_FULL = 'La clase esta llena.';
}

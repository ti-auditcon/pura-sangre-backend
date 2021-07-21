<?php

namespace App\Models\Invoicing;

use Illuminate\Support\Facades\DB;

class DTEErrors
{
    /**
     *  Errors for DTEs
     *
     *  @var  stdClass|string
     */
    protected $error;

    /**
     *  Instanciate with the errors passed and manage them
     *
     * @param   stdClass|string|null  $errors  Errors for DTEs
     */
    public function __construct($response)
    {
        $this->fillError($response);
        
        $this->manageErrors();
    }
    
    public function fillError($response)
    {
        if (isset($response->error)) {
            $this->error = $response->error;
        } else {
            $this->error = json_encode($response);
        }
    }
    /**
     *  Create record into the DB to save the errors
     *
     *  @return  void
     */
    public function manageErrors(): void
    {
        if (isset($this->error->message)) {
            $this->insertErrorIntoDB($this->error);
        }
    }

    /**
     *  [insertErrorIntoDB description]
     *
     *  @param   [type]  $errorName  [$errorName description]
     *
     *  @return  [type]              [return description]
     */
    public function insertErrorIntoDB($error)
    {
        $errorBody = isset($error->details) ? $error->details : 'sin detalles';

        DB::table('errors')->insert([
            'error' => 'al intentar emitir boleta al sii a traves de haulmer, dio el siguiente error: ' .  $error->message .
                        '. Con los siguientes errores: ' . json_encode($errorBody),
            'where' => 'FlowController@emiteReceiptToSII con el usuario: ' . auth()->user()->first_name . auth()->user()->id,
            'created_at' => now(),
        ]);
    }
}



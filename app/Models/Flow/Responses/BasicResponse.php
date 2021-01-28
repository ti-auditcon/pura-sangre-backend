<?php

namespace App\Models\Flow\Responses;

use App\Models\Flow\Fluid\Fluid;

class BasicResponse extends Fluid
{
    /**
     * Returns a fully functional string
     *
     * @return string|null
     */
    public function getUrl()
    {
        return $this->url && $this->token
            ? $this->url  . '?token=' . $this->token
            : null;
    }
}
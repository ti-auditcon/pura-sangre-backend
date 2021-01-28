<?php

namespace App\Models\Flow\Exceptions\Adapter;

use App\Models\Flow\Exceptions\FlowSdkException;
use Exception;
use Throwable;

class AdapterException extends Exception implements FlowSdkException
{
    protected $message = 'Flow did not respond successfully.';

    public function __construct(string $message = "", int $code = 0, Throwable $previous = null)
    {

        if (!empty($message)) {
            $this->message .= "\nTransaction data: $message";
        }

        parent::__construct($this->message, $code, $previous);
    }
}
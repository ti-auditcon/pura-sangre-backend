<?php

namespace App\Models\Flow\Exceptions\Transactions;

use App\Models\Flow\Exceptions\FlowSdkException;
use Psr\Http\Message\ResponseInterface;
use Throwable;

class TransactionException extends \Exception implements FlowSdkException
{

    protected $message = 'Flow reported an error: ';

    public function __construct(ResponseInterface $response, int $code = 0, Throwable $previous = null)
    {
        $content = json_decode($response->getBody()->getContents());

        $this->message .= 'Flow Code: ' . $content->code;
        $this->message .= 'Flow Message: ' . $content->message;

        parent::__construct($this->message, $response->getStatusCode(), $previous);
    }

}
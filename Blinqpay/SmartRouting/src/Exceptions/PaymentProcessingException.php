<?php

namespace Blinqpay\SmartRouting\Exceptions;

use Exception;

class PaymentProcessingException extends Exception
{
    public function __construct($message = "", $code = 0, Exception $previous = null)
    {
        $message = $message ?: "Failed to process the payment transaction";
        parent::__construct($message, $code, $previous);
    }
}
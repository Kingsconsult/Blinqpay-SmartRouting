<?php

namespace Blinqpay\SmartRouting\Exceptions;

use Exception;

class AdapterNotFoundException extends Exception
{
    public function __construct($adapterClass, $processorName, $message = "", $code = 0, Exception $previous = null)
    {
        $message = $message ?: "Adapter class '{$adapterClass}' not found for processor '{$processorName}'";
        parent::__construct($message, $code, $previous);
    }
}

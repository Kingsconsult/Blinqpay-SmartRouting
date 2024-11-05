<?php

namespace Blinqpay\SmartRouting\Adapters;

use Blinqpay\SmartRouting\Contracts\ProcessorAdapterInterface;

class Processor1Adapter implements ProcessorAdapterInterface
{
    public function processPayment(array $transactionData)
    {
        return [
            'status' => 'OK',
            'message' => 'Payment processed',
            'data' => $transactionData
        ];
    }
    
    public function refundPayment(array $transactionData)
    {
        return [
            'status' => 'OK',
            'message' => 'Refund Payment',
            'data' => $transactionData
        ];
    }
}

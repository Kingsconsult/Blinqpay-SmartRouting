<?php

namespace Blinqpay\SmartRouting\Tests;

use Blinqpay\SmartRouting\Adapters\Processor1Adapter;
use Blinqpay\SmartRouting\Tests\TestCase;


class Processor1AdapterTest extends TestCase
{
    public function test_process_payment()
    {
        $adapter = new Processor1Adapter();

        $transactionData = [
            'amount' => 100,
            'currency' => 'USD',
        ];

        $result = $adapter->processPayment($transactionData);

        $this->assertEquals($transactionData, $result['data']);
    }

    public function test_refund_payment()
    {
        $adapter = new Processor1Adapter();

        $transactionData = [
            'amount' => 100,
            'currency' => 'USD',
        ];

        $result = $adapter->refundPayment($transactionData);

        $this->assertEquals($transactionData, $result['data']);
    }
}

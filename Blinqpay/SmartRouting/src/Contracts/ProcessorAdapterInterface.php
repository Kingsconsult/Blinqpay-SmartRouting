<?php


namespace Blinqpay\SmartRouting\Contracts;

interface ProcessorAdapterInterface
{
    public function processPayment(array $transactionData);
    public function refundPayment(array $transactionData);
}

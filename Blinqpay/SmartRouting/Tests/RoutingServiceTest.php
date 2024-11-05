<?php

namespace Blinqpay\SmartRouting\Tests;

use Blinqpay\SmartRouting\Models\Processor;
use Blinqpay\SmartRouting\Services\RoutingRule;
use Blinqpay\SmartRouting\Services\RoutingService;
use Blinqpay\SmartRouting\Adapters\Processor1Adapter;
use Blinqpay\SmartRouting\Exceptions\AdapterNotFoundException;
use Blinqpay\SmartRouting\Exceptions\PaymentProcessingException;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Blinqpay\SmartRouting\Tests\TestCase;


class RoutingServiceTest extends TestCase
{

    use RefreshDatabase;

    protected $processor1;
    protected $processor2;
    protected $processor3;
    protected $processor4;
    protected $processor5;


    protected function setUp(): void
    {
        parent::setUp();

        $this->processor1 = 'Processor1'.now();
        $this->processor2 = 'Processor2'.now();
        $this->processor3 = 'Processor3'.now();
        $this->processor4 = 'Processor4'.now();
        $this->processor5 = 'Processor5'.now();

        // Example processor entries
        Processor::create([
            'name' => $this->processor1,
            'cost' => 1.5,
            'reliability' => 90,
            'currency_support' => ['USD'],
            'country_support' => ['US'],
            'adapter_class' => 'Blinqpay\SmartRouting\Adapters\Processor1Adapter',
            'status' => true,
        ]);

        Processor::create([
            'name' => $this->processor2,
            'cost' => 2.5,
            'reliability' => 85,
            'currency_support' => ['USD', 'EUR'],
            'country_support' => ['US', 'CA'],
            'adapter_class' => 'Blinqpay\SmartRouting\Adapters\Processor2Adapter',
            'status' => true,
        ]);
    }

    public function test_select_processor_based_on_routing_criteria()
    {
        $routingService = app(RoutingService::class);
        $routingRule = new RoutingRule([
            'currency' => 'USD',
            'country' => 'US',
            'max_cost' => 2.0,
            'min_reliability' => 85,
        ]);

        $routingService->addRule($routingRule);

        $selectedProcessor = $routingService->selectProcessor();

        // dd($selectedProcessor);

        $this->assertEquals($this->processor1 , $selectedProcessor["name"]);
    }

    /** @test */
    public function it_processes_payment_successfully()
    {
        // Arrange
        $transactionData = ['amount' => 100, 'currency' => 'USD'];
        $this->createProcessor([
            'name' => $this->processor3,
            'cost' => 2.5,
            'reliability' => 90,
            'currency_support' => ['USD', 'EUR'],
            'country_support' => ['US', 'CA'],
            'status' => true,
            'adapter_class' => Processor1Adapter::class,
        ]);

        $routingService = $this->app->make(RoutingService::class);
        $routingService->addRule(new RoutingRule([
            'currency' => 'USD',
            'country' => 'US',
            'max_cost' => 3,
            'min_reliability' => 80,
        ]));

        // Act
        $result = $routingService->processTransaction($transactionData);

        // Assert
        $this->assertNotNull($result);
        // Add more assertions to validate the result
    }

    /** @test */
    public function it_throws_exception_when_no_processor_matches_criteria()
    {
        // Arrange
        $transactionData = ['amount' => 100, 'currency' => 'NGN'];

        Processor::truncate();

        $routingService = $this->app->make(RoutingService::class);
        $routingService->addRule(new RoutingRule([
            'currency' => 'USD',
            'country' => 'US',
            'max_cost' => 3,
            'min_reliability' => 80,
        ]));

        // Act and Assert
        $this->expectException(PaymentProcessingException::class);
        $routingService->processTransaction($transactionData);
    }

    /** @test */
    public function it_throws_exception_when_adapter_not_found()
    {
        // Arrange
        $transactionData = ['amount' => 100, 'currency' => 'USD'];

        Processor::truncate();

        $this->createProcessor([
            'name' => '$this->processor5',
            'cost' => 2.5,
            'reliability' => 90,
            'currency_support' => ['USD', 'EUR'],
            'country_support' => ['NG', 'GH', 'US'],
            'status' => true,
            'adapter_class' => 'NonExistentAdapter',
        ]);

        $routingService = $this->app->make(RoutingService::class);
        $routingService->addRule(new RoutingRule([
            'currency' => 'USD',
            'country' => 'US',
            'max_cost' => 3,
            'min_reliability' => 80,
        ]));

        // Act and Assert
        $this->expectException(AdapterNotFoundException::class);
        $routingService->processTransaction($transactionData);
    }

    private function createProcessor(array $data): Processor
    {
        return Processor::create($data);
    }
}

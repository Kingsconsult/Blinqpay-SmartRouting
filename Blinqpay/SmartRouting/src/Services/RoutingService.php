<?php

namespace Blinqpay\SmartRouting\Services;

use Illuminate\Support\Facades\Log;
use Blinqpay\SmartRouting\Models\Processor;
use Blinqpay\SmartRouting\Contracts\ProcessorAdapterInterface;
use Blinqpay\SmartRouting\Exceptions\AdapterNotFoundException;
use Blinqpay\SmartRouting\Exceptions\PaymentProcessingException;

class RoutingService
{
    protected $rules = [];

    public function addRule(RoutingRule $rule)
    {
        $this->rules[] = $rule;
    }

    /**
     * Select the best processor and process payment through its adapter.
     *
     * @param array $transactionData
     * @return mixed
     * @throws PaymentProcessingException
     */
    public function processTransaction(array $transactionData)
    {
        $processor = $this->selectProcessor();
        
        if ($processor) {

            // Dynamically load the adapter class
            $adapterClass = $processor->adapter_class;

            if (class_exists($adapterClass)) {
                $adapter = new $adapterClass();
                
                if ($adapter instanceof ProcessorAdapterInterface) {
                    $startTime = microtime(true);
                    $result = $adapter->processPayment($transactionData);
                    $processingTime = microtime(true) - $startTime;
                    $this->trackProcessorPerformance($processor, true, $processingTime);
                    return $result;
                } else {
                    $message = "Adapter for {$processor->name} does not implement ProcessorAdapterInterface";
                    Log::error($message);
                    $this->trackProcessorPerformance($processor, false, null, $message);
                    throw new AdapterNotFoundException($adapterClass, $processor->name, $message);

                }
            } else {
                $message = "Adapter class {$adapterClass} not found for processor {$processor->name}";
                Log::error($message);
                $this->trackProcessorPerformance($processor, false, null, $message);
                throw new AdapterNotFoundException($adapterClass, $processor->name, $message);
            }
        } else {
            $message = "No processor matched the criteria";
            Log::warning($message);
            $this->trackProcessorPerformance(null, false, null, $message);
            throw new PaymentProcessingException($message);
        }

    }

    /**
     * Select the best processor based on routing rules.
     *
     * @return Processor|null
     */
    public function selectProcessor(): ?Processor
    {
        $processors = Processor::where('status', true)->get(); // Fetch active processors

        foreach ($processors as $processor) {
            foreach ($this->rules as $rule) {
                if ($rule->matches($processor)) {
                    Log::info("Processor selected: " . $processor->name);
                    return $processor;
                }
            }
        }

        Log::warning("No processor matched the criteria");
        return null;
    }

        /**
     * Track the performance of each payment processor.
     *
     * @param Processor $processor
     * @param bool $success
     * @param float|null $processingTime
     * @param string|null $errorMessage
     */
    protected function trackProcessorPerformance(Processor|null $processor, bool $success, ?float $processingTime = null, ?string $errorMessage = null)
    {
        $logData = [
            'processor' => $processor ? $processor->name : null,
            'success' => $success,
            'processing_time' => $processingTime,
            'error_message' => $errorMessage,
        ];

        Log::info('Payment processor performance', $logData);
    }
}

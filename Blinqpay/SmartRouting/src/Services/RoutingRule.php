<?php

namespace Blinqpay\SmartRouting\Services;

use Blinqpay\SmartRouting\Models\Processor;

class RoutingRule
{
    public $criteria;

    public function __construct(array $criteria)
    {
        $this->criteria = $criteria;
    }

    /**
     * Check if a processor matches the routing criteria based on currency and country.
     *
     * @param Processor $processor
     * @return bool
     */
    public function matches(Processor $processor): bool
    {
        // Check currency support
        if ((!in_array($this->criteria['currency'], $processor->currency_support)) ||
            (!in_array($this->criteria['country'], $processor->country_support)) ||
            ($processor->cost > $this->criteria['max_cost'])
        ) {
            return false;
        }



        return $processor->reliability >= $this->criteria['min_reliability'];
    }
}

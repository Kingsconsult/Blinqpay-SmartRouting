<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Blinqpay\SmartRouting\Models\Processor;
use Blinqpay\SmartRouting\Adapters\Processor1Adapter;

class ProcessorTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        if (Processor::count() === 0) {

            $processors = [
                [
                    'name' => 'Processor11',
                    'cost' => 2.5,
                    'reliability' => 90,
                    'currency_support' => ['USD', 'EUR'],
                    'country_support' => ['US', 'CA'],
                    'adapter_class' => Processor1Adapter::class,
                    'status' => true,
                ],
                [
                    'name' => 'Processor2',
                    'cost' => 1.8,
                    'reliability' => 85,
                    'currency_support' => ['USD', 'NGN'],
                    'country_support' => ['US', 'NG'],
                    'adapter_class' => Processor1Adapter::class,
                    'status' => true,
                ],
            ];

            foreach ($processors as $processorData) {
                Processor::create($processorData);
            }
        }
    }
}

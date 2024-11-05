<?php

namespace Blinqpay\SmartRouting\Services;

use Illuminate\Http\Request;
use Blinqpay\SmartRouting\Models\Processor;
use Illuminate\Database\Eloquent\Collection;
use Blinqpay\SmartRouting\Repositories\ProcessorRepository;


class ProcessorService
{

    protected ProcessorRepository $processorRepository;

    public function __construct(ProcessorRepository $processorRepository)
    {
        $this->processorRepository = $processorRepository;
    }


    public function addProcessor(Request $request): Processor
    {
        $processor = $request->validate([
            'name' => 'nullable',
            'cost' => 'nullable',
            'reliability' => 'nullable',
            'currency_support' => 'nullable|array',
            'country_support' => 'nullable|array',
            'statu' => 'nullable',
            'adapter_class' => 'nullable'
        ]);

        return $this->processorRepository->addProcessor($processor);
    }

    public function updateProcessor(int $id, Request $request): ?Processor
    {
        $processor = $request->validate([
            'name' => 'nullable',
            'cost' => 'nullable',
            'reliability' => 'nullable',
            'currency_support' => 'nullable|array',
            'country_support' => 'nullable|array',
            'statu' => 'nullable',
        ]);
        return $this->processorRepository->updateProcessor($id, $processor);
    }

    public function removeProcessor(int $id): bool
    {
        return $this->processorRepository->removeProcessor($id);
    }

    public function getActiveProcessors(): Collection
    {
        return $this->processorRepository->getActiveProcessors();
    }

    public function getAllProcessors(): Collection
    {
        return $this->processorRepository->getAllProcessors();
    }

    public function getAProcessor(int $id): Processor
    {
        return $this->processorRepository->getAProcessor($id);
    }
}

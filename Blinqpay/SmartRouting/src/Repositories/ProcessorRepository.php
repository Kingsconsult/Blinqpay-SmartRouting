<?php

namespace Blinqpay\SmartRouting\Repositories;

use Blinqpay\SmartRouting\Models\Processor;
use Illuminate\Database\Eloquent\Collection;

class ProcessorRepository
{
    public function addProcessor(array $data): Processor
    {
        return Processor::create($data);
    }

    public function updateProcessor(int $id, array $data): ?Processor
    {
        $processor = Processor::find($id);
        if ($processor) {
            $processor->update($data);
            return $processor;
        }
        return null;
    }

    public function removeProcessor(int $id): bool
    {
        $processor = Processor::find($id);
        if ($processor) {
            return $processor->delete();
        }
        return false;
    }

    public function getActiveProcessors(): Collection
    {
        return Processor::where('status', true)->get();
    }

    public function getAllProcessors(): Collection
    {
        return Processor::get();
    }

    public function getAProcessor($id): Processor
    {
        return Processor::find($id);
    }
}

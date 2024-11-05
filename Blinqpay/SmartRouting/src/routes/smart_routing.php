<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Blinqpay\SmartRouting\Models\Processor;
use Blinqpay\SmartRouting\Services\RoutingService;
use Blinqpay\SmartRouting\Services\ProcessorService;
use Blinqpay\SmartRouting\Repositories\ProcessorRepository;


Route::get('/test-routing', function (RoutingService $routingService) {
    $selectedProcessor = $routingService->selectProcessor();

    return response()->json([
        'selected_processor' => $selectedProcessor ? $selectedProcessor->name : 'No processor available'
    ]);
});


Route::prefix('processors')->group(function () {

    Route::get('/', function () {
        return (new ProcessorService(new ProcessorRepository()))->getAllProcessors();
    });


    Route::get('/actives', function () {
        return (new ProcessorService(new ProcessorRepository()))->getActiveProcessors();
    });
    Route::get('/{id}', function (int $id) {
        return (new ProcessorService(new ProcessorRepository()))->getAProcessor($id);
    });

    Route::post('/', function (Request $processor) {
        return (new ProcessorService(new ProcessorRepository()))->addProcessor($processor);
    });

    Route::put('/{id}', function (Request $request, $id) {
        return (new ProcessorService(new ProcessorRepository()))->updateProcessor($id, $request);
    });

    Route::delete('/{id}', function (int $id) {
        return (new ProcessorService(new ProcessorRepository()))->removeProcessor($id);
    });
});



Route::get('/process-transaction', function (RoutingService $routingService) {
    $transactionData = [
        'amount' => 100,
        'currency' => 'USD',
        'country' => 'US'
    ];

    $result = $routingService->processTransaction($transactionData);

    return response()->json([
        'result' => $result
    ]);
});

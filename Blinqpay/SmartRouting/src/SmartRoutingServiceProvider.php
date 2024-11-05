<?php

namespace Blinqpay\SmartRouting;

use Illuminate\Support\ServiceProvider;
use Blinqpay\SmartRouting\Services\RoutingRule;
use Blinqpay\SmartRouting\Services\RoutingService;
use Blinqpay\SmartRouting\Services\ProcessorService;
use Blinqpay\SmartRouting\Contracts\RoutingRuleInterface;
use Blinqpay\SmartRouting\Repositories\ProcessorRepository;
use Blinqpay\SmartRouting\Database\Seeders\ProcessorTableSeeder;

class SmartRoutingServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->loadRoutesFrom(__DIR__ . '/routes/smart_routing.php');

        $this->publishes([
            __DIR__ . '/Config/smart_routing.php' => config_path('smart_routing.php'),
        ], 'config');
        
        $this->publishes([
            __DIR__ . '/routes/smart_routing.php' => base_path('routes/vendor/Blinqpay/SmartRouting/smart_routing.php'),
        ], 'routes');

        // Publish migration file
        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__ . '/database/migrations/create_processors_table.php' => database_path('migrations/' . date('Y_m_d_His', time()) . '_create_processors_table.php'),
            ], 'migrations');

            $this->publishes([
                __DIR__ . '/database/seeders/ProcessorTableSeeder.php' => database_path('seeders/ProcessorTableSeeder.php'),
            ], 'seeders');
        }
    }

    public function register()
    {
        $this->mergeConfigFrom(
            __DIR__ . '/Config/smart_routing.php',
            'smart_routing'
        );

        $this->app->bind('smart-routing', function ($app) {
            return $app->make(RoutingService::class);
        });

        $this->app->singleton(RoutingService::class, function ($app) {
            $routingService = new RoutingService();

            // Load routing rule from config
            $rule = new RoutingRule(config('smart_routing.routing_criteria'));
            $routingService->addRule($rule);

            return $routingService;
        });

        // Register ProcessorRepository
        $this->app->singleton(ProcessorRepository::class, function ($app): ProcessorRepository {
            return new ProcessorRepository();
        });

        // Register ProcessorService
        $this->app->singleton(ProcessorService::class, function ($app) {
            return new ProcessorService(
                $app->make(ProcessorRepository::class)
            );
        });
    }

}

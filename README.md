# Blinqpay Smart Routing Laravel Package

[![Software License](https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square)](LICENSE.md)

## Introduction
A Laravel package for intelligent payment routing that dynamically selects the best payment processor based on configurable criteria such as cost, reliability, currency, and country support.

## Features

- **Dynamic Routing Logic**: Routes payments to the optimal processor based on factors like transaction cost, reliability, currency, and country.
- **Adapter Pattern**: Supports the addition of new payment processors with unique APIs through an adapter interface.
- **Database-Backed Processor Management**: Stores processor details in the database, enabling dynamic updates without code changes.
- **Configurable Criteria**: Set custom routing criteria for processor selection.
- **Error Logging and Monitoring**: Logs processor selection, errors, and exceptions.
- **Built-In Testing Support**: Easily run unit tests to validate the package's functionality.


## Installation

   **Add the Package to Your Project**

   ```bash
   composer require blinqpay/smart-routing
   ```


## Manual Installation
   In your Laravel project, add the package via a path repository in your `composer.json`

   ```json
   "repositories": [
       {
           "type": "path",
           "url": "./packages/Blinqpay/SmartRouting"
       }
   ],
   "require": {
       "blinqpay/smart-routing": "*"
   }
   ```


  publish configuration and Migrations

   ```bash
    php artisan vendor:publish --provider="Blinqpay\SmartRouting\SmartRoutingServiceProvider" --tag="config"
    php artisan vendor:publish --provider="Blinqpay\SmartRouting\SmartRoutingServiceProvider" --tag="migrations"
    php artisan vendor:publish --provider="Blinqpay\SmartRouting\SmartRoutingServiceProvider" --tag="seeders"
    php artisan vendor:publish --provider="Blinqpay\SmartRouting\SmartRoutingServiceProvider" --tag="routes"
    php artisan migrate
    php artisan db:seed ProcessorTableSeeder
   ```

   install the package

   ```bash
   composer update
   ```


## Usage

   To check available routes

   ```bash
     php artisan route:list 
   ```


1. Dynamically select the routing

    /test-routing


2. Process Transaction with the best routing

    /process-transaction

    $transactionData = [
        'amount' => 100,
        'currency' => 'USD',
        'country' => 'US'
    ];

    
3. Adding a New Processor

    /processors

    Each processor requires a name, cost, reliability, supported currencies, supported countries, status, and an adapter class that implements the ProcessorAdapterInterface'

      ```json
    {
        "name" : "Lin",
        "cost" : 43.2,
        "reliability" : 10,
        "currency_support" : ["NGN"],
        "country_support" : ["NG"],
        "adapter_class" : "Blinqpay\\SmartRouting\\Adapters\\Processor1Adapter"
    }
   ```


5. Get all Active processors

    `/processors/active`  - GET Request

6. Get all processors

    `/processors`  - GET Request

7. Get one processors

    `/processors/{id}`  - GET Request

8. Delete one processors

    `/processors/{id}`  - DELETE Request

9. Update one processors

    `/processors/{id}`  - PUT Request

    ```json
    {
        "name" : "Lin",
        "cost" : 43.2,
        "reliability" : 10,
        "currency_support" : ["NGN"],
        "country_support" : ["NG"],
        "adapter_class" : "Blinqpay\\SmartRouting\\Adapters\\Processor1Adapter"
    }
    ```



## Testing

./vendor/bin/phpunit

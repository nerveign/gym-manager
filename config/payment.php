<?php

return [
    
    /*
    |--------------------------------------------------------------------------
    | Payment Gateway Configuration
    |--------------------------------------------------------------------------
    |
    | Configuration for Doovera payment gateway integration
    |
    */

    'api_key' => env('PAYMENT_API_KEY', 'WW4HbMSBj0osmsSuswJMHUnZgGartNUC'),
    
    'base_url' => env('PAYMENT_BASE_URL', 'https://payment-dummy.doovera.com/api/v1'),
    
    'webhook_secret' => env('PAYMENT_WEBHOOK_SECRET', 'EcisNAcIcGNrNYygaRKW1K8ptuhDPRpU'),
    
    'expired_hours' => env('PAYMENT_EXPIRED_HOURS', 24),

    /*
    |--------------------------------------------------------------------------
    | Payment Status Mapping
    |--------------------------------------------------------------------------
    |
    | Map Doovera payment statuses to internal statuses
    |
    */

    'status_mapping' => [
        'paid' => 'success',
        'success' => 'success',
        'completed' => 'success',
        'settlement' => 'success',
        'pending' => 'pending',
        'expired' => 'failed',
        'cancelled' => 'failed',
        'failed' => 'failed',
    ],

    /*
    |--------------------------------------------------------------------------
    | API Endpoints
    |--------------------------------------------------------------------------
    |
    | Doovera API endpoints configuration
    |
    */

    'endpoints' => [
        'create_va' => '/create-virtual-account',
        'check_payment' => '/check-payment',
        'webhook' => '/webhook',
    ],

];

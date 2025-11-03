<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Midtrans Configuration
    |--------------------------------------------------------------------------
    |
    | Configuration for Midtrans payment gateway integration
    |
    */

    'server_key' => env('MIDTRANS_SERVER_KEY'),
    'client_key' => env('MIDTRANS_CLIENT_KEY'),
    'is_production' => env('MIDTRANS_IS_PRODUCTION', false),
    'is_sanitized' => env('MIDTRANS_IS_SANITIZED', true),
    'is_3ds' => env('MIDTRANS_IS_3DS', true),
    
    // Sandbox URLs
    'sandbox_base_url' => 'https://api.sandbox.midtrans.com/v2',
    'sandbox_snap_url' => 'https://app.sandbox.midtrans.com/snap/snap.js',
    
    // Production URLs  
    'production_base_url' => 'https://api.midtrans.com/v2',
    'production_snap_url' => 'https://app.midtrans.com/snap/snap.js',
];

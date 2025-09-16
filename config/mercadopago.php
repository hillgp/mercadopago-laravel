<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Configurações do Mercado Pago
    |--------------------------------------------------------------------------
    |
    | Configure aqui as credenciais do Mercado Pago. Use as variáveis de
    | ambiente (.env) para valores sensíveis. Modo: 'sandbox' para testes,
    | 'production' para produção.
    |
    */

    'access_token' => env('MERCADOPAGO_ACCESS_TOKEN', ''),

    'public_key' => env('MERCADOPAGO_PUBLIC_KEY', ''),

    'modo' => env('MERCADOPAGO_MODO', 'sandbox'), // 'sandbox' ou 'production'

    /*
    |--------------------------------------------------------------------------
    | Configurações Adicionais
    |--------------------------------------------------------------------------
    |
    | Outras configurações opcionais para o SDK do Mercado Pago.
    |
    */

    'sandbox' => [
        'access_token' => env('MERCADOPAGO_SANDBOX_ACCESS_TOKEN', ''),
        'public_key' => env('MERCADOPAGO_SANDBOX_PUBLIC_KEY', ''),
    ],

    'production' => [
        'access_token' => env('MERCADOPAGO_PRODUCTION_ACCESS_TOKEN', ''),
        'public_key' => env('MERCADOPAGO_PRODUCTION_PUBLIC_KEY', ''),
    ],

    /*
    |--------------------------------------------------------------------------
    | URL Base
    |--------------------------------------------------------------------------
    |
    | URL base para o SDK, ajustável para sandbox/produção.
    |
    */

    'url_base' => [
        'sandbox' => 'https://api.mercadopago.com',
        'production' => 'https://api.mercadopago.com',
    ],

];
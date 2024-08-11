<?php

return [
    'link' => env('HYPERPAY_LINK'),
    'access_token' => env('HYPERPAY_ACCESS_TOKEN'),
    'access_token_apple_pay' => env('HYPERPAY_ACCESS_TOKEN_APPLE_PAY'),
    'entity_id' => env('HYPERPAY_ENTITY_ID'),
    'entity_id_mada' => env('HYPERPAY_ENTITY_ID_MADA'),
    'entity_id_apple_pay' => env('HYPERPAY_ENTITY_ID_APPLE_PAY'),
    'payment_type' => env('HYPERPAY_PAYMENT_TYPE'),
    'currency' => env('HYPERPAY_CURRENCY'),
    'payment_methods' => env('HYPERPAY_PAYMENT_METHODS'),
    'test_user_ids' => json_decode(env('PAYMENT_TEST_USER_ID', '[]'), true),
];

<?php

return [
    'access_token' => env('AUTH_TOKEN'),
    'entity_id' => env('ENTITY_ID'),
    'entity_id_mada' => env('ENTITY_ID_MADA'),
    'payment_type' => env('HYPERPAY_PAYMENT_TYPE'),
    'payment_methods' => env('HYPERPAY_PAYMENT_METHODS'),
    'recurring_entity_id'=> env('RECURRING_ENTITY_ID'),

    'snb_entity_id_apple_pay'=> env('SNB_APPLE_PAY_ENTITY_ID'),
    'snb_apple_pay_token'=> env('SNB_APPLE_PAY_ACCESS_TOKEN'),

    'ryd_entity_id_apple_pay'=> env('RYD_APPLE_PAY_ENTITY_ID'),
    'ryd_apple_pay_token'=> env('RYD_APPLE_PAY_ACCESS_TOKEN'),

];

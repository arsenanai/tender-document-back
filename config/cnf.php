<?php
return [
    'PAGINATION_SIZE' => env('VITE_PAGINATION_SIZE', 20),
    'APP_DEBUG' => env('APP_DEBUG', false),
    'BRAND_TITLE' => env('BRAND_TITLE', 'Entries Checking App'),
    'PAD_PARTNER_ID' => env('PAD_PARTNER_ID', 2),
    'PAD_SUBPARTNER_ID' => env('PAD_SUBPARTNER_ID', 2),
    'ID_PAD' => env('ID_PAD', 3),
    'ADMIN_NAME' => env('ADMIN_NAME', 'Admin'),
    'ADMIN_EMAIL' => env('ADMIN_EMAIL', 'admin@entries.com'),
    'ADMIN_INITIAL_PASSWORD' => env('ADMIN_INITIAL_PASSWORD', 'Entries#2023'),
];
<?php

/*
* Sets all configuration data for Mercado Livre based on .env entries.
*/

return [
    'is_active' => env('ENABLE_MERCADO_LIVRE', false),
    'api_base_url' => 'https://api.mercadolibre.com/',
];
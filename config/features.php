<?php
/**
 * To enable a feature, set the ENABLE_{NAME}=true in your .env file
 */
return [
    "multistore" => env("ENABLE_MULTISTORE", false),
    "currency_switcher" => env("ENABLE_CURRENCY_SWITCHER", false),
    "lang_switcher" => env("ENABLE_LANG_SWITCHER", false),
    "marketplace" => env("ENABLE_MARKETPLACE", false),
    "attributes" => env("ENABLE_OLD_ATTR_STYLE", false),
    "customer_message" => env("ENABLE_CUSTOMER_MESSAGE", false),
    "aex_shipping" => env("ENABLE_AEX_SHIPPING", false),
    "melhorenvio_shipping" => env("ENABLE_MELHORENVIO_SHIPPING", false),
    "custom_product" => env("ENABLE_CUSTOM_PRODUCT", false),
    "compras_paraguai" => env("ENABLE_COMPRAS_PARAGUAI", false),
    "productsListPdf" => env("ENABLE_PRODUCTS_LIST_PDF", false),
    "api" => env("ENABLE_API", false),
    "custom_product_number" =>env("ENABLE_CUSTOM_PRODUCT", false),
    "color_gallery" => env("ENABLE_COLOR_GALLERY", false),
    "material_gallery" => env("ENABLE_MATERIAL_GALLERY", false),
    "disable_developed_by_us" => env("DISABLE_DEVELOPED_BY_US", false),
    "redplay_digital_product" => env("ENABLE_REDPLAY_DIGITAL_PRODUCT", false),
    "consult_the_price_by_whatsapp" => env("CONSULT_THE_PRICE_BY_WHATSAPP", false),
    "fedex_shipping" => env("ENABLE_FEDEX_SHIPPING", false),
    'wedding_list' => env('ENABLE_WEDDING_LIST', false),
];


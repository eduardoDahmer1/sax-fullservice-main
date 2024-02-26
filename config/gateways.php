<?php
/**
 * To enable a gateway, set the WITH_{NAME}=true in your .env file
 */
  return [
    "bancard" => env("WITH_BANCARD", false),
    "mercado_pago" => env("WITH_MERCADO_PAGO", false),
    "cielo" => env("WITH_CIELO", false),
    "pagarme" => env("WITH_PAGARME", false),
    "pagseguro" => env("WITH_PAGSEGURO", false),
    "pagopar" => env("WITH_PAGOPAR", false),
    "rede" => env("WITH_REDE", false),
    "paghiper" => env("WITH_PAGHIPER", false),
    "paypal" => env("WITH_PAYPAL", false),
    "paghiper_pix" => env("WITH_PAGHIPER_PIX", false),
    "pay42" => env("WITH_PAY42", false),
];

  return [
    'gateway' => env("ENABLE_GATEWAY", false)
  ];

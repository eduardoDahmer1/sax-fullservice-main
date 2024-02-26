<?php
/**
 * To enable a feature, set the ENABLE_{NAME}=true in your .env file
 */
if(!env("DOCTYPE_CPF") && !env("DOCTYPE_CNPJ") ){
    return [
        "general" => true
    ];
}

return [
    "cpf"=>env("DOCTYPE_CPF", false),
    "cnpj"=> env("DOCTYPE_CNPJ", false),
];


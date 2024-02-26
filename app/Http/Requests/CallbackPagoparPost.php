<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class CallbackPagoparPost extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            "resultado" => "required|array",
            "resultado.0.pagado" => "required|boolean",
            "resultado.0.numero_comprobante_interno" => "nullable",
            "resultado.0.ultimo_mensaje_error" => "nullable",
            "resultado.0.forma_pago" => "required|string",
            "resultado.0.fecha_pago" => "nullable",
            "resultado.0.monto" => "required|string",
            "resultado.0.fecha_maxima_pago" => "required|string",
            "resultado.0.hash_pedido" => "required|string",
            "resultado.0.numero_pedido" => "required|string",
            "resultado.0.cancelado" => "required|boolean",
            "resultado.0.forma_pago_identificador" => "required|string",
            "resultado.0.token" => "required|string",
            "respuesta"=> "required|boolean",
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json("Incorrect payload", 422));
    }
}

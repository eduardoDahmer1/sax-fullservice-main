<?php

namespace App\Classes;

class MelhorEnvio
{
    public $url;
    public $authorization_code;

    public $productionUrl = "https://www.melhorenvio.com.br";
    public $sandboxUrl = "https://sandbox.melhorenvio.com.br";

    public function __construct($token, $production = false)
    {
        $this->token = $token;
        $this->url = $production ? $this->productionUrl : $this->sandboxUrl;
    }

    /**
     * @return json - Array
     */
    public function getBalance()
    {
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => $this->url . "/api/v2/me/balance",
            CURLOPT_RETURNTRANSFER => 1,
            CURLOPT_HTTPHEADER => array(
                "Accept: application/json",
                "Content-Type: application/json",
                "Authorization: Bearer $this->token"
              )
        ));
        $resp = curl_exec($curl);
        $resp = json_decode($resp);

        return $resp;
    }

    /**
     * @param string $from - String - Origin postal code
     * @param string $to - String - Destination postal code
     * @param object $package - Package Object
     * @param double $package->height - Height of package
     * @param double $package->width - Width of package
     * @param double $package->length - Length of package
     * @param double $package->weight - Weight of package
     * @param array  $products - products with measures
     * @param object $options - Options Object
     * @param double $options->insurance_value - Insurance value
     * @param bool   $options->receipt - Receipt
     * @param bool   $options->own_hand - Own Hand
     * @param bool   $options->collect - Collect
     * @param string $services - Services ids ("1,2")
     * @return json - Array
     */
    public function getRates($origin, $destination, $package = null, $products = null, $options, $services)
    {
        $request = (object)[
            "from" => (object)[
                "postal_code" => $origin
            ],
            "to" => (object)[
                "postal_code" => $destination
            ],
            "package" => $package,
            "products" => $products,
            "options" => $options,
            "services" => $services
        ];

        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => $this->url . "/api/v2/me/shipment/calculate",
            CURLOPT_RETURNTRANSFER => 1,
            CURLOPT_POST => 1,
            CURLOPT_POSTFIELDS => json_encode($request),
            CURLOPT_HTTPHEADER => array(
                "Accept: application/json",
                "Content-Type: application/json",
                "Authorization: Bearer $this->token"
              )
        ));
        $resp = curl_exec($curl);
        $resp = json_decode($resp);

        return $resp;
    }

    /**
     * @param int    $service - Integer - Service Id
     * @param int    $agency - Integer - Agency Id (JadLog required)
     * @param object $from - From Object
     * @param object $to - To Object
     * @param object $products - Products Object
     * @param object $package - Package Object
     * @param object $options - Options Object
     * @param string $coupon - String - Discount Coupon
     * @return json - Array
     */
    public function addToCart($service, $agency, $from, $to, $products, $package, $options, $coupon)
    {
        $request = (object)[
            "service" => $service,
            "agency" => $agency,
            "from" => $from,
            "to" => $to,
            "products" => $products,
            "package" => $package,
            "options" => $options,
            "coupon" => $coupon
        ];

        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => $this->url . "/api/v2/me/cart",
            CURLOPT_RETURNTRANSFER => 1,
            CURLOPT_POST => 1,
            CURLOPT_POSTFIELDS => json_encode($request),
            CURLOPT_HTTPHEADER => array(
                "Accept: application/json",
                "Content-Type: application/json",
                "Authorization: Bearer $this->token"
              )
        ));
        $resp = curl_exec($curl);
        $resp = json_decode($resp);

        return $resp;
    }

    /**
     * @param array    $orders - Array - Orders uuid array
     * @return json - Array
     */
    public function checkoutCart($orders)
    {
        $request = (object)[
            "orders" => $orders
        ];

        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => $this->url . "/api/v2/me/shipment/checkout",
            CURLOPT_RETURNTRANSFER => 1,
            CURLOPT_POST => 1,
            CURLOPT_POSTFIELDS => json_encode($request),
            CURLOPT_HTTPHEADER => array(
                "Accept: application/json",
                "Content-Type: application/json",
                "Authorization: Bearer $this->token"
              )
        ));
        $resp = curl_exec($curl);
        $resp = json_decode($resp);

        return $resp;
    }

    /**
     * @param array    $orders - Array - Orders uuid array
     * @return json - Array
     */
    public function preview($orders)
    {
        $request = (object)[
            "orders" => $orders
        ];

        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => $this->url . "/api/v2/me/shipment/preview",
            CURLOPT_RETURNTRANSFER => 1,
            CURLOPT_POST => 1,
            CURLOPT_POSTFIELDS => json_encode($request),
            CURLOPT_HTTPHEADER => array(
                "Accept: application/json",
                "Content-Type: application/json",
                "Authorization: Bearer $this->token"
              )
        ));
        $resp = curl_exec($curl);
        $resp = json_decode($resp);

        return $resp;
    }

    /**
     * @param array    $orders - Array - Orders uuid array
     * @return json - Array
     */
    public function print($orders)
    {
        $request = (object)[
            "mode" => "private",
            "orders" => $orders
        ];

        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => $this->url . "/api/v2/me/shipment/print",
            CURLOPT_RETURNTRANSFER => 1,
            CURLOPT_POST => 1,
            CURLOPT_POSTFIELDS => json_encode($request),
            CURLOPT_HTTPHEADER => array(
                "Accept: application/json",
                "Content-Type: application/json",
                "Authorization: Bearer $this->token"
              )
        ));
        $resp = curl_exec($curl);
        $resp = json_decode($resp);

        return $resp;
    }

    /**
     * @param array    $orders - Array - Orders uuid array
     * @return json - Array
     */
    public function generate($orders)
    {
        $request = (object)[
            "orders" => $orders
        ];

        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => $this->url . "/api/v2/me/shipment/generate",
            CURLOPT_RETURNTRANSFER => 1,
            CURLOPT_POST => 1,
            CURLOPT_POSTFIELDS => json_encode($request),
            CURLOPT_HTTPHEADER => array(
                "Accept: application/json",
                "Content-Type: application/json",
                "Authorization: Bearer $this->token"
              )
        ));
        $resp = curl_exec($curl);
        $resp = json_decode($resp);

        return $resp;
    }

    /**
     * @param string    $order - string - Orders uuid
     * @param int       $reason_id - int - Reason id (2-Desistance / 4-Wrong informations / 5-Rejected by shipping company)
     * @param string    $description - string - Description
     * @return json - Array
     */
    public function cancel($order, $description)
    {
        $request = [(object)[
            "id" => $order,
            "reason_id" => 2,
            "description" => $description
        ]];

        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => $this->url . "/api/v2/me/shipment/cancel",
            CURLOPT_RETURNTRANSFER => 1,
            CURLOPT_POST => 1,
            CURLOPT_POSTFIELDS => json_encode($request),
            CURLOPT_HTTPHEADER => array(
                "Accept: application/json",
                "Content-Type: application/json",
                "Authorization: Bearer $this->token"
              )
        ));
        $resp = curl_exec($curl);
        $resp = json_decode($resp);

        return $resp;
    }

    /**
     * @param array    $orders - Array - Orders uuid array
     * @return json - Array
     */
    public function getOrderTracking($orders)
    {
        $request = (object)[
            "orders" => $orders
        ];

        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => $this->url . "/api/v2/me/shipment/tracking",
            CURLOPT_RETURNTRANSFER => 1,
            CURLOPT_POST => 1,
            CURLOPT_POSTFIELDS => json_encode($request),
            CURLOPT_HTTPHEADER => array(
                "Accept: application/json",
                "Content-Type: application/json",
                "Authorization: Bearer $this->token"
              )
        ));
        $resp = curl_exec($curl);
        $resp = json_decode($resp);

        return $resp;
    }

    /**
     * @param string $order - String - Order uuid
     * @return json - Array
     */
    public function getOrderInfo($order)
    {
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => $this->url . "/api/v2/me/orders/".$order,
            CURLOPT_RETURNTRANSFER => 1,
            CURLOPT_HTTPHEADER => array(
                "Accept: application/json",
                "Content-Type: application/json",
                "Authorization: Bearer $this->token"
              )
        ));
        $resp = curl_exec($curl);
        $resp = json_decode($resp);

        return $resp;
    }

    /**
     * @return json - Array - List of companies and services.
     */
    public static function getCompanies()
    {
        $productionUrl = "https://www.melhorenvio.com.br";

        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => $productionUrl . "/api/v2/me/shipment/companies",
            CURLOPT_RETURNTRANSFER => 1
        ));

        $resp = curl_exec($curl);

        $resp = json_decode($resp, JSON_OBJECT_AS_ARRAY);

        return $resp;
    }

    /**
     * @param int    $company_id - Integer - Company id
     * @param string $state_abbr - String - State Abreviation
     * @param string $city - String - City
     * @return json - Array - List of agencies.
     */
    public static function getAgencies($company_id = null, $state_abbr = null, $city = null)
    {
        $productionUrl = "https://www.melhorenvio.com.br";

        $options = "";
        $options .= $company_id != null ? "company=".$company_id."&" : "";
        $options .= $state_abbr != null ? "state=".$state_abbr."&" : "";
        $options .= $city != null ? "city=".$city : "";

        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => $productionUrl . "/api/v2/me/shipment/agencies?".$options,
            CURLOPT_RETURNTRANSFER => 1
        ));

        $resp = curl_exec($curl);

        $resp = json_decode($resp, JSON_OBJECT_AS_ARRAY);

        return $resp;
    }
}

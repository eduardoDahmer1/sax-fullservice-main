<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class OrderResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $data = $this->cart;
        $cart = $data;
        $cart_items = [];
        if ($cart != null) {
            foreach ($cart->items as $item) {
                if ($item != null) {
                    $cart_item = [
                        "sku" => $item['item']['sku'],
                        "ref_code" => $item['item']['ref_code'],
                        "external_name" => $item['item']['external_name'],
                        "price" => $item['item']['price'],
                        "qty" => $item['qty'],
                        "total_price" => $item['price']
                    ];
                    $translations = $item['item']->translations()->get(['locale','name']);
                    foreach ($translations as $translation) {
                        $cart_item['name'][$translation->locale] = $translation->name;
                    }
                    $cart_items[] = (object)$cart_item;
                }
            }
        }

        $order = [
            "id" => $this->id,
            "method" => $this->method,
            "shipping" => $this->shipping,
            "pickup_location" => $this->pickup_location,
            "pay_amount" => $this->pay_amount,
            "order_number" => $this->order_number,
            "payment_status" => $this->payment_status,
            "customer_name" => $this->customer_name,
            "customer_email" => $this->customer_email,
            "customer_document" => $this->customer_document,
            "customer_phone" => $this->customer_phone,
            "customer_country" => $this->customer_country,
            "customer_state" => $this->customer_state,
            "customer_city" => $this->customer_city,
            "customer_district" => $this->customer_district,
            "customer_address" => $this->customer_address,
            "customer_address_number" => $this->customer_address_number,
            "customer_complement" => $this->customer_complement,
            "customer_zip" => $this->customer_zip,
            "shipping_name" => $this->shipping_name,
            "shipping_email" => $this->shipping_email,
            "shipping_document" => $this->shipping_document,
            "shipping_phone" => $this->shipping_phone,
            "shipping_country" => $this->shipping_country,
            "shipping_state" => $this->shipping_state,
            "shipping_city" => $this->shipping_city,
            "shipping_district" => $this->shipping_district,
            "shipping_address" => $this->shipping_address,
            "shipping_address_number" => $this->shipping_address_number,
            "shipping_complement" => $this->shipping_complement,
            "shipping_zip" => $this->shipping_zip,
            "order_note" => $this->order_note,
            "status" => $this->status,
            "currency_sign" => $this->currency_sign,
            "currency_value" => $this->currency_value,
            "shipping_cost" => $this->shipping_cost,
            "packing_cost" => $this->packing_cost,
            "shipping_type" => $this->shipping_type,
            "packing_type" => $this->packing_type,
            "tax" => $this->tax,
            "coupon_code" => $this->coupon_code,
            "coupon_discount" => $this->coupon_discount,
            "created_at" => $this->created_at,
            "updated_at" => $this->updated_at,
            "cart_items" => $cart_items,
            "puntoentrega" => $this->puntoentrega
        ];

        return $order;
    }
}

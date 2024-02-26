<?php

namespace App\Models;

use App\Classes\MelhorEnvio;
use App\Observers\OrderObserver;
use Spatie\Activitylog\LogOptions;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;

class Order extends CachedModel
{
    use LogsActivity;

    protected $fillable = [
        'user_id',
        'cart',
        'method',
        'shipping',
        'pickup_location',
        'store_id',
        'totalQty',
        'pay_amount',
        'txnid',
        'charge_id',
        'order_number',
        'payment_status',
        'customer_email',
        'customer_name',
        'customer_phone',
        'customer_address',
        'customer_zip',
        'shipping_name',
        'shipping_email',
        'shipping_phone',
        'shipping_address',
        'shipping_zip',
        'order_note',
        'internal_note',
        'status',
        'shipping_country',
        'shipping_city',
        'shipping_state',
        'shipping_document',
        'shipping_complement',
        'customer_country',
        'customer_city',
        'customer_state',
        'customer_document',
        'customer_complement',
        'shipping_type',
        'shipping_cost',
        'packing_cost',
        'packing_type',
        'is_qrcode',
        'pay42_due_date',
        'pay42_total',
        'pay42_exchange_rate',
        'pay42_billet',
        'puntoentrega',
        'puntoid',
        'order_number_cec',
        'billing',
    ];
    protected $dispatchesEvents = [
        'created' => OrderObserver::class,
        'updated' => OrderObserver::class,
    ];
    
    protected $casts = [
        'cart' => 'array'
    ];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->useLogName('orders')
            ->logFillable()
            ->logOnlyDirty();
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function weddingProducts()
    {
        return $this->belongsToMany(WeddingProduct::class, 'order_wedding_product', 'order_id', 'wedding_product_id')
            ->withPivot('wedding_product_id');
    }
    
    public function vendororders()
    {
        return $this->hasMany('App\Models\VendorOrder');
    }

    public function tracks()
    {
        return $this->hasMany('App\Models\OrderTrack', 'order_id');
    }

    public function melhorenvio_requests()
    {
        $order_store_id = explode(';', trim(strstr(strstr($this->internal_note, '#:['), ']', true), '#:['))[0];

        $melhorenvio_requests = MelhorenvioRequest::where('order_id', $this->id)->get();

        $orderStoreSettings = Generalsetting::find($order_store_id);
        if (!isset($orderStoreSettings)) {
            $orderStoreSettings = resolve('storeSettings');
        }
        $melhorenvio = new MelhorEnvio($orderStoreSettings->melhorenvio->token, $orderStoreSettings->melhorenvio->production);

        foreach ($melhorenvio_requests as &$request) {
            $orderinfo = $melhorenvio->getOrderInfo($request->uuid);
            if (!empty($orderinfo->id)) {
                if ($request->status != $orderinfo->status) {
                    $track = new OrderTrack;
                    switch ($orderinfo->status) {
                        case 'pending':
                            $status_str = __('Pending');
                            break;
                        case 'released':
                            $status_str = __('Released');
                            break;
                        case 'posted':
                            $status_str = __('Posted');
                            break;
                        case 'delivered':
                            $status_str = __('Delivered');
                            break;
                        case 'canceled':
                            $status_str = __('Canceled');
                            break;
                        case 'undelivered':
                            $status_str = __('Undelivered');
                            break;
                        default:
                            $status_str = __('Unknown');
                            break;
                    }
                    $track->title = $status_str;
                    $track->text = __('Tracking code').': '
                        .$orderinfo->tracking
                        .' - https://www.melhorrastreio.com.br/meu-rastreio/'.$orderinfo->tracking;
                    $track->order_id = $this->id;
                    $track->save();
                }

                $request->status = $orderinfo->status;
                $request->created_at = $orderinfo->created_at;
                $request->paid_at = $orderinfo->paid_at;
                $request->generated_at = $orderinfo->generated_at;
                $request->posted_at = $orderinfo->posted_at;
                $request->delivered_at = $orderinfo->delivered_at;
                $request->canceled_at = $orderinfo->canceled_at;
                $request->expired_at = $orderinfo->expired_at;
                $request->tracking = $orderinfo->tracking;

                $request->save();
            }
            if (empty($request->preview_url)) {
                $preview = $melhorenvio->preview([$request->uuid]);
                if (!empty($preview->url)) {
                    $request->preview_url = $preview->url;
                    $request->save();
                }
            }
            if (empty($request->print_url)) {
                $print = $melhorenvio->print([$request->uuid]);
                if (!empty($print->url)) {
                    $request->print_url = $print->url;
                    $request->save();
                }
            }
        }

        return $this->hasMany('App\Models\MelhorenvioRequest', 'order_id');
    }

    public static function scopeMercadoLivreOrders($query)
    {
        return $query->where('method', 'Pagamento Externo via Mercado Livre');
        ;
    }

    public static function scopeMercadoLivreOrdersCompleted($query)
    {
        return $query->MercadoLivreOrders()->where('status', 'completed');
    }

    public static function scopeMercadoLivreOrdersPending($query)
    {
        return $query->MercadoLivreOrders()->where('status', 'pending');
        ;
    }

    public static function scopeMercadoLivreOrdersProcessing($query)
    {
        return $query->MercadoLivreOrders()->where('status', 'processing');
        ;
    }
}

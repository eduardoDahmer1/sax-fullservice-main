<?php

namespace App\Models;

use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class Generalsetting extends LocalizedModel
{
    use LogsActivity;


    protected $with = ['translations'];

    protected $translatedAttributes = [
        'title',
        'footer',
        'copyright',
        'cod_text',
        'popup_title',
        'popup_text',
        'maintain_text',
        'bancard_text',
        'mercadopago_text',
        'cielo_text',
        'pagseguro_text',
        'pagopar_text',
        'bank_text',
        'pagarme_text',
        'rede_text',
        'page_not_found_text',
        'policy',
        'crow_policy',
        'privacy_policy',
        'vendor_policy'
    ];

    protected $fillable = [
        'logo',
        'favicon',
        'title',
        'header_email',
        'header_phone',
        'colors',
        'loader',
        'admin_loader',
        'talkto',
        'map_key',
        'disqus',
        'stripe_key',
        'stripe_secret',
        'currency_format',
        'withdraw_fee',
        'withdraw_charge',
        'tax',
        'shipping_cost',
        'smtp_host',
        'smtp_port',
        'smtp_user',
        'smtp_pass',
        'from_email',
        'from_name',
        'add_cart',
        'out_stock',
        'already_cart',
        'add_wish',
        'already_wish',
        'wish_remove',
        'add_compare',
        'already_compare',
        'compare_remove',
        'color_change',
        'coupon_found',
        'no_coupon',
        'already_coupon',
        'order_title',
        'order_text',
        'is_affilate',
        'affilate_charge',
        'affilate_banner',
        'fixed_commission',
        'percentage_commission',
        'multiple_shipping',
        'vendor_ship_info',
        'paypal_text',
        'stripe_text',
        'header_color',
        'footer_color',
        'footer_text_color',
        'copyright_color',
        'menu_color',
        'menu_hover_color',
        'is_verification_email',
        'instamojo_key',
        'instamojo_token',
        'instamojo_text',
        'instamojo_sandbox',
        'paystack_key',
        'paystack_email',
        'paystack_text',
        'wholesell',
        'is_capcha',
        'error_banner',
        'popup_background',
        'invoice_logo',
        'user_image',
        'vendor_color',
        'is_secure',
        'paypal_business',
        'footer_logo',
        'email_encryption',
        'paytm_merchant',
        'paytm_secret',
        'paytm_website',
        'paytm_industry',
        'is_paytm',
        'paytm_text',
        'paytm_mode',
        'molly_key',
        'molly_text',
        'razorpay_key',
        'razorpay_secret',
        'razorpay_text',
        'domain',
        'product_percent',
        'ref_color',
        'bancard_public_key',
        'bancard_private_key',
        'bancard_mode',
        'mercadopago_access_token',
        'whatsapp_number',
        'simplified_checkout_number',
        'is_correios',
        'correios_cep',
        'localcep_start',
        'localcep_end',
        'correios_width',
        'correios_height',
        'correios_length',
        'correios_weight',
        'cielo_merchantid',
        'pagseguro_token',
        'pagseguro_email',
        'is_jivochat',
        'jivochat',
        'is_pagopar',
        'pagopar_public_key',
        'pagopar_private_key',
        'country_ship',
        'ftp_folder',
        'pagarme_encryption_key',
        'pagarme_api_key',
        'is_comprasparaguai',
        'store_comprasparaguai',
        'is_lojaupdate',
        'store_lojaupdate',
        'rede_token',
        'rede_pv',
        'rede_installments',
        'rede_minimum_installment_price',
        'currency_id',
        'is_aex',
        'is_aex_production',
        'is_aex_insurance',
        'aex_public',
        'aex_private',
        'aex_origin',
        'aex_calle_principal',
        'aex_calle_transversal',
        'aex_numero_casa',
        'aex_telefono',
        'is_melhorenvio',
        'is_fedex',
        'is_zip_validation',
        'company_document',
        'document_name',
        'pagarme_installments',
        'show_products_without_stock',
        'show_products_without_stock_baw',
        'is_paypal',
        'is_paypal_sandbox',
        'paypal_secret',
        'paypal_client_id',
        'is_attr_cards',
        'is_invoice_photo',
        'paghiper_api_key',
        'paghiper_token',
        'is_paghiper',
        'paghiper_days_due_date',
        'paghiper_is_discount',
        'paghiper_discount',
        'is_maintain',
        'is_dark_mode',
        'is_blog',
        'is_cart_abandonment',
        'is_back_in_stock',
        'paghiper_pix_days_due_date',
        'paghiper_pix_discount',
        'is_pay42_pix',
        'is_pay42_sandbox',
        'pay42_token',
        'pay42_currency',
        'pay42_due_date',
        'is_pay42_billet',
        'is_pay42_card',
        'is_complete_profile',
        'is_brands'
    ];

    public $timestamps = false;

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->useLogName('generalsetting')
            ->logFillable()
            ->logOnlyDirty();
    }


    public function upload($name, $file, $oldname)
    {
        $file->move('storage/images', $name);
        if ($oldname != null) {
            if (file_exists(public_path() . '/storage/images/' . $oldname)) {
                unlink(public_path() . '/storage/images/' . $oldname);
            }
        }
    }

    public function pagesettings()
    {
        return $this->hasOne('App\Models\Pagesetting', 'store_id');
    }

    public function products()
    {
        return $this->belongsToMany('App\Models\Product', 'product_store', 'store_id', 'product_id');
    }

    public function banners()
    {
        return $this->belongsToMany('App\Models\Banner', 'banner_store', 'store_id', 'banner_id');
    }

    public function sliders()
    {
        return $this->belongsToMany('App\Models\Slider', 'slider_store', 'store_id', 'slider_id');
    }

    public function defaultLang()
    {
        return $this->belongsTo('App\Models\Language', 'lang_id');
    }

    public function melhorenvio()
    {
        return $this->belongsTo('App\Models\MelhorenvioConf', 'melhorenvio_id')->withDefault();
    }

    public function fedex()
    {
        return $this->belongsTo('App\Models\FedexConf', 'fedex_id')->withDefault();
    }

    public function getFaviconUrlAttribute()
    {
        return $this->favicon ? asset("storage/images/{$this->favicon}") : asset('assets/images/favicon.png');
    }

    public function getLogoUrlAttribute()
    {
        return $this->logo ? asset("storage/images/{$this->logo}") : asset('assets/images/logo_azul.png');
    }

    public function getInvoiceLogoUrlAttribute()
    {
        return $this->invoice_logo ? asset("storage/images/{$this->invoice_logo}") : asset('assets/images/logo_azul.png');
    }

    public function getFooterLogoUrlAttribute()
    {
        return $this->footer_logo ? asset("storage/images/{$this->footer_logo}") : asset('assets/images/logo_branca.png');
    }

    public function getAdminLoaderUrlAttribute()
    {
        return $this->admin_loader ? asset("storage/images/{$this->admin_loader}") : asset('assets/images/loader.gif');
    }

    public function getLoaderUrlAttribute()
    {
        return $this->loader ? asset("storage/images/{$this->loader}") : asset('assets/images/loader.gif');
    }

    public function getErrorBannerUrlAttribute()
    {
        return $this->error_banner ? asset("storage/images/{$this->error_banner}") : asset('assets/images/404.png');
    }

    public function getUserImageUrlAttribute()
    {
        return $this->user_image ? asset("storage/images/{$this->user_image}") : asset('assets/images/user.jpg');
    }

    public function getPopupBackgroundUrlAttribute()
    {
        return asset("storage/images/{$this->popup_background}");
    }
}

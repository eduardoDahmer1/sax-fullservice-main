<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGeneralsettingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('generalsettings', function (Blueprint $table) {
            $table->integer('id', true);
            $table->string('logo', 191)->nullable();
            $table->string('favicon', 191);
            $table->text('header_email')->nullable();
            $table->text('header_phone')->nullable();
            $table->string('colors', 191)->nullable();
            $table->string('loader', 191);
            $table->string('admin_loader', 191)->nullable();
            $table->tinyInteger('is_talkto')->default(1);
            $table->text('talkto')->nullable();
            $table->tinyInteger('is_jivochat');
            $table->string('jivochat')->nullable();
            $table->tinyInteger('team_show_whatsapp');
            $table->tinyInteger('team_show_header');
            $table->tinyInteger('team_show_footer');
            $table->tinyInteger('is_language')->default(1);
            $table->tinyInteger('is_loader')->default(1);
            $table->text('map_key')->nullable();
            $table->tinyInteger('is_disqus')->default(0);
            $table->longText('disqus')->nullable();
            $table->tinyInteger('is_contact')->default(0);
            $table->tinyInteger('is_faq')->default(0);
            $table->tinyInteger('guest_checkout')->default(0);
            $table->tinyInteger('stripe_check')->default(0);
            $table->tinyInteger('cod_check')->default(0);
            $table->text('stripe_key')->nullable();
            $table->text('stripe_secret')->nullable();
            $table->tinyInteger('currency_format')->default(0);
            $table->double('withdraw_fee')->default(0);
            $table->double('withdraw_charge')->default(0);
            $table->double('tax')->default(0);
            $table->double('shipping_cost')->default(0);
            $table->string('smtp_host', 191)->nullable();
            $table->string('smtp_port', 191)->nullable();
            $table->string('smtp_user', 191)->nullable();
            $table->string('smtp_pass', 191)->nullable();
            $table->string('from_email', 191)->nullable();
            $table->string('from_name', 191)->nullable();
            $table->tinyInteger('is_smtp')->default(0);
            $table->tinyInteger('is_comment')->default(1);
            $table->tinyInteger('is_currency')->default(1);
            $table->text('add_cart')->nullable();
            $table->text('out_stock')->nullable();
            $table->text('add_wish')->nullable();
            $table->text('already_wish')->nullable();
            $table->text('wish_remove')->nullable();
            $table->text('add_compare')->nullable();
            $table->text('already_compare')->nullable();
            $table->text('compare_remove')->nullable();
            $table->text('color_change')->nullable();
            $table->text('coupon_found')->nullable();
            $table->text('no_coupon')->nullable();
            $table->text('already_coupon')->nullable();
            $table->text('order_title')->nullable();
            $table->text('order_text')->nullable();
            $table->tinyInteger('is_affilate')->default(1);
            $table->integer('affilate_charge')->default(0);
            $table->text('affilate_banner')->nullable();
            $table->text('already_cart')->nullable();
            $table->double('fixed_commission')->default(0);
            $table->double('percentage_commission')->default(0);
            $table->tinyInteger('multiple_shipping')->default(0);
            $table->tinyInteger('multiple_packaging')->default(0);
            $table->tinyInteger('vendor_ship_info')->default(0);
            $table->tinyInteger('reg_vendor')->default(0);
            $table->text('paypal_text')->nullable();
            $table->text('stripe_text')->nullable();
            $table->string('header_color', 191)->nullable();
            $table->string('footer_color', 191)->nullable();
            $table->string('footer_text_color')->nullable();
            $table->string('copyright_color', 191)->nullable();
            $table->tinyInteger('is_admin_loader')->default(0);
            $table->string('menu_color', 191)->nullable();
            $table->string('menu_hover_color', 191)->nullable();
            $table->tinyInteger('is_home')->default(0);
            $table->tinyInteger('is_verification_email')->default(0);
            $table->string('instamojo_key', 191)->nullable();
            $table->string('instamojo_token', 191)->nullable();
            $table->text('instamojo_text')->nullable();
            $table->tinyInteger('is_instamojo')->default(0);
            $table->tinyInteger('instamojo_sandbox')->default(0);
            $table->tinyInteger('is_paystack')->default(0);
            $table->text('paystack_key')->nullable();
            $table->text('paystack_email')->nullable();
            $table->text('paystack_text')->nullable();
            $table->integer('wholesell')->default(0);
            $table->tinyInteger('is_capcha')->default(0);
            $table->string('error_banner', 191)->nullable();
            $table->tinyInteger('is_popup')->default(0);
            $table->tinyInteger('is_newsletter_popup')->default(0);
            $table->text('popup_background')->nullable();
            $table->string('invoice_logo', 191)->nullable();
            $table->string('user_image', 191)->nullable();
            $table->string('vendor_color', 191)->nullable();
            $table->tinyInteger('is_secure')->default(0);
            $table->tinyInteger('is_report');
            $table->tinyInteger('paypal_check')->nullable()->default(0);
            $table->text('paypal_business')->nullable();
            $table->text('footer_logo')->nullable();
            $table->string('email_encryption', 191)->nullable();
            $table->text('paytm_merchant')->nullable();
            $table->text('paytm_secret')->nullable();
            $table->text('paytm_website')->nullable();
            $table->text('paytm_industry')->nullable();
            $table->integer('is_paytm')->default(1);
            $table->text('paytm_text')->nullable();
            $table->enum('paytm_mode', ['sandbox', 'live'])->nullable();
            $table->tinyInteger('is_molly')->default(0);
            $table->text('molly_key')->nullable();
            $table->text('molly_text')->nullable();
            $table->integer('is_razorpay')->default(1);
            $table->text('razorpay_key')->nullable();
            $table->text('razorpay_secret')->nullable();
            $table->text('razorpay_text')->nullable();
            $table->tinyInteger('show_stock')->default(0);
            $table->tinyInteger('is_maintain')->default(0);
            $table->string('domain');
            $table->tinyInteger('is_default')->default(0);
            $table->integer('product_percent')->default(0);
            $table->unsignedInteger('lang_id');
            $table->unsignedInteger('currency_id');
            $table->tinyInteger('show_currency_values');
            $table->tinyInteger('show_product_prices')->default(1);
            $table->tinyInteger('reference_code');
            $table->string('ref_color')->nullable();
            $table->tinyInteger('is_cart')->default(1);
            $table->tinyInteger('is_simplified_checkout')->default(1);
            $table->string('simplified_checkout_number')->nullable();
            $table->tinyInteger('is_standard_checkout')->default(1);
            $table->tinyInteger('is_bancard');
            $table->text('bancard_public_key')->nullable();
            $table->text('bancard_private_key')->nullable();
            $table->enum('bancard_mode', ['sandbox', 'live'])->nullable();
            $table->tinyInteger('is_mercadopago');
            $table->text('mercadopago_access_token')->nullable();
            $table->tinyInteger('is_whatsapp')->default(0);
            $table->string('whatsapp_number')->nullable();
            $table->tinyInteger('is_correios');
            $table->string('correios_cep')->nullable();
            $table->string('localcep_start')->nullable();
            $table->string('localcep_end')->nullable();
            $table->integer('correios_width')->nullable();
            $table->integer('correios_height')->nullable();
            $table->integer('correios_length')->nullable();
            $table->double('correios_weight', 8, 2)->nullable();
            $table->tinyInteger('is_cielo');
            $table->text('cielo_merchantid')->nullable();
            $table->tinyInteger('is_pagseguro');
            $table->text('pagseguro_token')->nullable();
            $table->text('pagseguro_email')->nullable();
            $table->tinyInteger('is_pagseguro_sandbox');
            $table->char('country_ship', 2)->nullable();
            $table->tinyInteger('is_pagopar');
            $table->text('pagopar_public_key')->nullable();
            $table->text('pagopar_private_key')->nullable();
            $table->tinyInteger('bank_check');
            $table->string('ftp_folder')->nullable();
            $table->tinyInteger('is_pagarme');
            $table->text('pagarme_encryption_key')->nullable();
            $table->text('pagarme_api_key')->nullable();
            $table->tinyInteger('is_comprasparaguai')->default(0);
            $table->integer('store_comprasparaguai')->default(1);
            $table->tinyInteger('is_lojaupdate')->default(0);
            $table->integer('store_lojaupdate')->default(1);
            $table->tinyInteger('switch_highlight_currency')->default(0);
            $table->tinyInteger('is_rede');
            $table->tinyInteger('is_rede_sandbox')->default(0);
            $table->text('rede_token')->nullable();
            $table->text('rede_pv')->nullable();
            $table->tinyInteger('rede_installments');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('generalsettings');
    }
}

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Helpers\Helper;

class CreateDatabase extends Migration
{
    public function up()
    {
        Schema::create('generalsettings', function (Blueprint $table) {
            $table->integer('id', true);
            $table->string('logo', 191)->nullable();
            $table->string('favicon', 191)->nullable();
            $table->text('header_email')->nullable();
            $table->text('header_phone')->nullable();
            $table->string('colors', 191)->nullable();
            $table->string('loader', 191)->nullable();
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
        Schema::create('languages', function (Blueprint $table) {
            $table->integer('id', true);
            $table->tinyInteger('is_default')->default(0);
            $table->string('language', 100)->nullable();
            $table->string('file', 100)->nullable();
            $table->string('locale');
            $table->string('file_extras')->nullable();
            $table->string('extras_name')->nullable();
            $table->tinyInteger('rtl')->default(0);
        });
        Schema::create('currencies', function (Blueprint $table) {
            $table->integer('id', true);
            $table->string('name', 30);
            $table->string('sign', 10);
            $table->double('value');
            $table->tinyInteger('is_default')->default(0);
            $table->char('decimal_separator', 1)->default('.');
            $table->char('thousands_separator', 1)->nullable()->default('');
            $table->integer('decimal_digits')->default(2);
        });
        Schema::create('activity_log', function (Blueprint $table) {
            $table->increments('id');
            $table->string('log_name')->nullable()->index();
            $table->text('description');
            $table->integer('subject_id')->nullable();
            $table->string('subject_type')->nullable();
            $table->integer('causer_id')->nullable();
            $table->string('causer_type')->nullable();
            $table->text('properties')->nullable();
            $table->timestamps();
        });
        Schema::create('admin_languages', function (Blueprint $table) {
            $table->integer('id', true);
            $table->tinyInteger('is_default')->default(0);
            $table->string('language', 100);
            $table->string('file', 100);
            $table->string('name', 100);
            $table->tinyInteger('rtl')->default(0);
        });
        Schema::create('admin_user_conversations', function (Blueprint $table) {
            $table->integer('id', true);
            $table->string('subject', 191);
            $table->integer('user_id');
            $table->text('message');
            $table->timestamps();
            $table->enum('type', ['Ticket', 'Dispute'])->nullable();
            $table->text('order_number')->nullable();
        });
        Schema::create('admin_user_messages', function (Blueprint $table) {
            $table->integer('id', true);
            $table->integer('conversation_id');
            $table->text('message');
            $table->integer('user_id')->nullable();
            $table->timestamps();
        });
        Schema::create('admins', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name', 191);
            $table->string('email', 191)->unique();
            $table->string('phone', 191);
            $table->integer('role_id')->default(0);
            $table->string('photo', 191)->nullable();
            $table->string('password', 191);
            $table->tinyInteger('status')->default(1);
            $table->rememberToken();
            $table->timestamps();
            $table->text('shop_name')->nullable();
        });
        Schema::create('attribute_option_translations', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('attribute_option_id');
            $table->string('locale')->index();
            $table->string('name');
            $table->unique(['attribute_option_id', 'locale']);
        });
        Schema::create('attribute_options', function (Blueprint $table) {
            $table->integer('id', true);
            $table->integer('attribute_id')->nullable()->index();
            $table->timestamps();
        });
        Schema::create('attribute_translations', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('attribute_id');
            $table->string('locale')->index();
            $table->string('name');
            $table->unique(['attribute_id', 'locale']);
        });
        Schema::create('attributes', function (Blueprint $table) {
            $table->integer('id', true);
            $table->integer('attributable_id')->nullable();
            $table->string('attributable_type')->nullable();
            $table->string('input_name')->nullable();
            $table->integer('price_status')->default(1)->comment('0 - hide, 1- show	');
            $table->tinyInteger('show_price');
            $table->integer('details_status')->default(1)->comment('0 - hide, 1- show	');
            $table->timestamps();
        });
        Schema::create('bank_accounts', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->text('info');
            $table->tinyInteger('status')->default(0);
        });
        Schema::create('banner_store', function (Blueprint $table) {
            $table->unsignedInteger('banner_id')->index();
            $table->unsignedInteger('store_id')->index();
        });
        Schema::create('banners', function (Blueprint $table) {
            $table->integer('id', true);
            $table->string('photo', 191)->nullable();
            $table->string('link', 191)->nullable();
            $table->enum('type', ['Large', 'TopSmall', 'BottomSmall']);
        });
        Schema::create('blog_categories', function (Blueprint $table) {
            $table->integer('id', true);
            $table->string('slug', 191);
        });
        Schema::create('blog_category_translations', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('blog_category_id');
            $table->string('locale')->index();
            $table->string('name');
            $table->unique(['blog_category_id', 'locale']);
        });
        Schema::create('blog_translations', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('blog_id');
            $table->string('locale')->index();
            $table->string('title');
            $table->text('details');
            $table->text('meta_tag')->nullable();
            $table->text('meta_description')->nullable();
            $table->text('tags')->nullable();
            $table->unique(['blog_id', 'locale']);
        });
        Schema::create('blogs', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('category_id');
            $table->string('photo', 191)->nullable();
            $table->string('source', 191);
            $table->integer('views')->default(0);
            $table->tinyInteger('status')->default(1);
            $table->timestamp('created_at')->nullable();
        });
        Schema::create('brands', function (Blueprint $table) {
            $table->increments('id');
            $table->string('ref_code')->nullable()->index();
            $table->string('name');
            $table->string('slug');
            $table->string('image')->nullable();
            $table->integer('status')->default(1);
            $table->integer('partner')->nullable();
        });
        Schema::create('categories', function (Blueprint $table) {
            $table->integer('id', true);
            $table->string('slug', 191);
            $table->tinyInteger('status')->default(1);
            $table->string('photo', 191)->nullable();
            $table->tinyInteger('is_featured')->default(0);
            $table->string('image', 191)->nullable();
        });
        Schema::create('category_translations', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('category_id');
            $table->string('locale')->index();
            $table->string('name');
            $table->unique(['category_id', 'locale']);
        });
        Schema::create('childcategories', function (Blueprint $table) {
            $table->integer('id', true);
            $table->integer('subcategory_id')->index();
            $table->string('slug', 191);
            $table->tinyInteger('status')->default(1);
            $table->integer('category_id')->nullable()->index();
        });
        Schema::create('childcategory_translations', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('childcategory_id');
            $table->string('locale')->index();
            $table->string('name');
            $table->unique(['childcategory_id', 'locale']);
        });
        Schema::create('cities', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->unsignedInteger('state_id')->index();
        });
        Schema::create('comments', function (Blueprint $table) {
            $table->integer('id', true);
            $table->unsignedInteger('user_id');
            $table->unsignedInteger('product_id');
            $table->text('text');
            $table->timestamp('created_at')->default('0000-00-00 00:00:00');
            $table->timestamp('updated_at')->useCurrent();
        });
        Schema::create('conversations', function (Blueprint $table) {
            $table->integer('id', true);
            $table->string('subject', 191);
            $table->integer('sent_user');
            $table->integer('recieved_user');
            $table->text('message');
            $table->timestamps();
        });
        Schema::create('counters', function (Blueprint $table) {
            $table->integer('id', true);
            $table->enum('type', ['referral', 'browser'])->default('referral');
            $table->string('referral')->nullable();
            $table->integer('total_count')->default(0);
            $table->integer('todays_count')->default(0);
            $table->date('today')->nullable();
        });
        Schema::create('countries', function (Blueprint $table) {
            $table->integer('id', true);
            $table->string('country_code', 2)->default('');
            $table->string('country_name', 100)->default('');
        });
        Schema::create('coupons', function (Blueprint $table) {
            $table->integer('id', true);
            $table->string('code', 191);
            $table->tinyInteger('type');
            $table->double('price');
            $table->string('times', 191)->nullable();
            $table->unsignedInteger('used')->default(0);
            $table->tinyInteger('status')->default(1);
            $table->date('start_date');
            $table->date('end_date');
        });
        Schema::create('email_template_translations', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('email_template_id');
            $table->string('locale')->index();
            $table->string('email_subject');
            $table->text('email_body');
            $table->unique(['email_template_id', 'locale']);
        });
        Schema::create('email_templates', function (Blueprint $table) {
            $table->integer('id', true);
            $table->string('email_type')->nullable();
            $table->integer('status')->default(1);
        });
        Schema::create('failed_jobs', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->text('connection');
            $table->text('queue');
            $table->longText('payload');
            $table->longText('exception');
            $table->timestamp('failed_at')->useCurrent();
        });
        Schema::create('faq_translations', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('faq_id');
            $table->string('locale')->index();
            $table->string('title');
            $table->text('details');
            $table->unique(['faq_id', 'locale']);
        });
        Schema::create('faqs', function (Blueprint $table) {
            $table->increments('id');
            $table->tinyInteger('status')->nullable()->default(0);
        });
        Schema::create('favorite_sellers', function (Blueprint $table) {
            $table->integer('id', true);
            $table->integer('user_id');
            $table->integer('vendor_id');
        });
        Schema::create('galleries', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('product_id');
            $table->string('photo', 191);
        });
        Schema::create('galleries360', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('product_id');
            $table->string('photo');
        });
        Schema::create('generalsetting_translations', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('generalsetting_id');
            $table->string('locale')->index();
            $table->string('title');
            $table->text('footer')->nullable();
            $table->text('copyright')->nullable();
            $table->string('cod_text')->nullable();
            $table->string('popup_title')->nullable();
            $table->text('popup_text')->nullable();
            $table->text('maintain_text')->nullable();
            $table->text('bancard_text')->nullable();
            $table->text('mercadopago_text')->nullable();
            $table->text('cielo_text')->nullable();
            $table->text('pagseguro_text')->nullable();
            $table->text('pagopar_text')->nullable();
            $table->text('bank_text')->nullable();
            $table->text('pagarme_text')->nullable();
            $table->text('rede_text')->nullable();
            $table->unique(['generalsetting_id', 'locale']);
        });
        Schema::create('jobs', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('queue')->index();
            $table->longText('payload');
            $table->unsignedTinyInteger('attempts');
            $table->unsignedInteger('reserved_at')->nullable();
            $table->unsignedInteger('available_at');
            $table->unsignedInteger('created_at');
        });
        Schema::create('messages', function (Blueprint $table) {
            $table->integer('id', true);
            $table->integer('conversation_id');
            $table->text('message');
            $table->integer('sent_user')->nullable();
            $table->integer('recieved_user')->nullable();
            $table->timestamps();
        });
        Schema::create('notifications', function (Blueprint $table) {
            $table->integer('id', true);
            $table->unsignedInteger('order_id')->nullable();
            $table->integer('user_id')->nullable();
            $table->integer('vendor_id')->nullable();
            $table->integer('product_id')->nullable();
            $table->integer('conversation_id')->nullable();
            $table->tinyInteger('is_read')->default(0);
            $table->timestamps();
        });
        Schema::create('order_track_translations', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('order_track_id');
            $table->string('locale')->index();
            $table->text('title')->nullable();
            $table->text('text')->nullable();
            $table->unique(['order_track_id', 'locale']);
        });
        Schema::create('order_tracks', function (Blueprint $table) {
            $table->integer('id', true);
            $table->integer('order_id');
            $table->timestamps();
        });
        Schema::create('orders', function (Blueprint $table) {
            $table->integer('id', true);
            $table->integer('user_id')->nullable();
            $table->text('cart');
            $table->string('method')->nullable();
            $table->string('shipping')->nullable();
            $table->string('pickup_location')->nullable();
            $table->string('totalQty', 191);
            $table->float('pay_amount', 10, 0);
            $table->string('txnid')->nullable();
            $table->string('charge_id')->nullable();
            $table->string('order_number');
            $table->string('payment_status');
            $table->string('customer_email');
            $table->string('customer_name');
            $table->string('customer_country', 191);
            $table->string('customer_phone');
            $table->string('customer_address')->nullable();
            $table->string('customer_city')->nullable();
            $table->string('customer_zip')->nullable();
            $table->string('shipping_name')->nullable();
            $table->string('shipping_country', 191)->nullable();
            $table->string('shipping_email')->nullable();
            $table->string('shipping_phone')->nullable();
            $table->string('shipping_address')->nullable();
            $table->string('shipping_city')->nullable();
            $table->string('shipping_zip')->nullable();
            $table->text('order_note')->nullable();
            $table->string('coupon_code', 191)->nullable();
            $table->string('coupon_discount', 191)->nullable();
            $table->enum('status', ['pending', 'processing', 'completed', 'declined', 'on delivery'])->default('pending');
            $table->timestamps();
            $table->string('affilate_user', 191)->nullable();
            $table->string('affilate_charge', 191)->nullable();
            $table->string('currency_sign', 10);
            $table->double('currency_value');
            $table->double('shipping_cost');
            $table->double('packing_cost')->default(0);
            $table->integer('tax');
            $table->tinyInteger('dp')->default(0);
            $table->text('pay_id')->nullable();
            $table->integer('vendor_shipping_id')->default(0);
            $table->integer('vendor_packing_id')->default(0);
            $table->string('shipping_type')->nullable();
            $table->string('packing_type')->nullable();
            $table->string('customer_state')->nullable();
            $table->string('customer_document')->nullable();
            $table->string('customer_complement')->nullable();
            $table->string('shipping_state')->nullable();
            $table->string('shipping_document')->nullable();
            $table->string('shipping_complement')->nullable();
            $table->string('customer_address_number')->nullable();
            $table->string('customer_district')->nullable();
            $table->string('shipping_address_number')->nullable();
            $table->string('shipping_district')->nullable();
        });
        Schema::create('package_translations', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('package_id');
            $table->string('locale')->index();
            $table->string('title');
            $table->string('subtitle')->nullable();
            $table->unique(['package_id', 'locale']);
        });
        Schema::create('packages', function (Blueprint $table) {
            $table->integer('id', true);
            $table->integer('user_id')->default(0);
            $table->double('price')->default(0);
        });
        Schema::create('page_translations', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('page_id');
            $table->string('locale')->index();
            $table->string('title');
            $table->text('details');
            $table->text('meta_tag')->nullable();
            $table->text('meta_description')->nullable();
            $table->unique(['page_id', 'locale']);
        });
        Schema::create('pages', function (Blueprint $table) {
            $table->integer('id', true);
            $table->string('slug', 191);
            $table->tinyInteger('header')->default(0);
            $table->tinyInteger('footer')->default(0);
        });
        Schema::create('pagesetting_translations', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('pagesetting_id');
            $table->string('locale')->index();
            $table->text('contact_success')->nullable();
            $table->text('contact_title')->nullable();
            $table->text('contact_text')->nullable();
            $table->text('side_title')->nullable();
            $table->text('side_text')->nullable();
            $table->unique(['pagesetting_id', 'locale']);
        });
        Schema::create('pagesettings', function (Blueprint $table) {
            $table->increments('id');
            $table->string('contact_email', 191);
            $table->text('street')->nullable();
            $table->text('phone')->nullable();
            $table->text('fax')->nullable();
            $table->text('email')->nullable();
            $table->text('site')->nullable();
            $table->tinyInteger('slider')->default(1);
            $table->tinyInteger('service')->default(1);
            $table->tinyInteger('featured')->default(1);
            $table->tinyInteger('small_banner')->default(1);
            $table->tinyInteger('best')->default(1);
            $table->tinyInteger('top_rated')->default(1);
            $table->tinyInteger('large_banner')->default(1);
            $table->tinyInteger('big')->default(1);
            $table->tinyInteger('hot_sale')->default(1);
            $table->tinyInteger('partners')->default(0);
            $table->tinyInteger('review_blog')->default(1);
            $table->text('best_seller_banner')->nullable();
            $table->text('best_seller_banner_link')->nullable();
            $table->text('big_save_banner')->nullable();
            $table->text('big_save_banner_link')->nullable();
            $table->tinyInteger('bottom_small')->default(0);
            $table->tinyInteger('flash_deal')->default(0);
            $table->text('best_seller_banner1')->nullable();
            $table->text('best_seller_banner_link1')->nullable();
            $table->text('big_save_banner1')->nullable();
            $table->text('big_save_banner_link1')->nullable();
            $table->integer('featured_category')->default(0);
            $table->integer('store_id')->default(1)->index();
        });
        Schema::create('partners', function (Blueprint $table) {
            $table->integer('id', true);
            $table->string('photo', 191)->nullable();
            $table->string('link', 191)->nullable();
        });
        Schema::create('payment_gateways', function (Blueprint $table) {
            $table->integer('id', true);
            $table->string('subtitle', 191)->nullable();
            $table->string('title', 191);
            $table->text('details')->nullable();
            $table->tinyInteger('status')->default(1);
        });
        Schema::create('pickup_translations', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('pickup_id');
            $table->string('locale')->index();
            $table->string('location');
            $table->unique(['pickup_id', 'locale']);
        });
        Schema::create('pickups', function (Blueprint $table) {
            $table->increments('id');
        });
        Schema::create('product_clicks', function (Blueprint $table) {
            $table->integer('id', true);
            $table->unsignedInteger('product_id')->index();
            $table->date('date')->nullable();
        });
        Schema::create('product_store', function (Blueprint $table) {
            $table->unsignedInteger('product_id')->index();
            $table->unsignedInteger('store_id')->index();
        });
        Schema::create('product_translations', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('product_id');
            $table->string('locale')->index();
            $table->string('name');
            $table->mediumText('details')->nullable();
            $table->string('ship')->nullable();
            $table->text('policy')->nullable();
            $table->text('meta_tag')->nullable();
            $table->text('meta_description')->nullable();
            $table->text('features')->nullable();
            $table->string('tags')->nullable();
            $table->unique(['product_id', 'locale']);
        });
        Schema::create('products', function (Blueprint $table) {
            $table->increments('id');
            $table->string('sku')->nullable();
            $table->enum('product_type', ['normal', 'affiliate'])->default('normal');
            $table->text('affiliate_link')->nullable();
            $table->integer('user_id')->default(0);
            $table->integer('category_id')->nullable()->index();
            $table->integer('subcategory_id')->nullable()->index();
            $table->integer('childcategory_id')->nullable()->index();
            $table->text('attributes')->nullable();
            $table->text('slug')->nullable();
            $table->string('photo')->nullable();
            $table->string('thumbnail')->nullable();
            $table->string('file', 191)->nullable();
            $table->string('size', 191)->nullable();
            $table->string('size_qty', 191)->nullable();
            $table->string('size_price', 191)->nullable();
            $table->text('color')->nullable();
            $table->double('price');
            $table->double('previous_price')->nullable();
            $table->integer('stock')->nullable();
            $table->unsignedTinyInteger('status')->default(1);
            $table->unsignedInteger('views')->default(0);
            $table->text('colors')->nullable();
            $table->tinyInteger('product_condition')->default(0);
            $table->tinyInteger('is_meta')->default(0);
            $table->string('youtube', 191)->nullable();
            $table->enum('type', ['Physical', 'Digital', 'License']);
            $table->text('license')->nullable();
            $table->text('license_qty')->nullable();
            $table->text('link')->nullable();
            $table->string('platform')->nullable();
            $table->string('region')->nullable();
            $table->string('licence_type')->nullable();
            $table->string('measure', 191)->nullable();
            $table->unsignedTinyInteger('featured')->default(0);
            $table->unsignedTinyInteger('best')->default(0);
            $table->unsignedTinyInteger('top')->default(0);
            $table->unsignedTinyInteger('hot')->default(0);
            $table->unsignedTinyInteger('latest')->default(0);
            $table->unsignedTinyInteger('big')->default(0);
            $table->tinyInteger('trending')->default(0);
            $table->tinyInteger('sale')->default(0);
            $table->timestamps();
            $table->tinyInteger('is_discount')->default(0);
            $table->text('discount_date')->nullable();
            $table->text('whole_sell_qty')->nullable();
            $table->text('whole_sell_discount')->nullable();
            $table->tinyInteger('is_catalog')->default(0);
            $table->integer('catalog_id')->default(0);
            $table->unsignedInteger('brand_id')->nullable()->index('products_brand_id_foreign');
            $table->string('ref_code', 50)->nullable()->index();
            $table->unsignedInteger('ref_code_int')->nullable()->index();
            $table->string('mpn', 50)->nullable();
            $table->tinyInteger('free_shipping')->nullable();
            $table->unsignedInteger('max_quantity')->nullable();
            $table->double('weight')->nullable();
            $table->integer('width')->nullable();
            $table->integer('height')->nullable();
            $table->integer('length')->nullable();
            $table->string('external_name')->nullable();
        });
        Schema::create('ratings', function (Blueprint $table) {
            $table->integer('id', true);
            $table->integer('user_id');
            $table->integer('product_id');
            $table->text('review')->nullable();
            $table->tinyInteger('rating');
            $table->dateTime('review_date');
        });
        Schema::create('replies', function (Blueprint $table) {
            $table->integer('id', true);
            $table->unsignedInteger('user_id');
            $table->unsignedInteger('comment_id');
            $table->text('text');
            $table->timestamp('created_at')->default('0000-00-00 00:00:00');
            $table->timestamp('updated_at')->default('0000-00-00 00:00:00');
        });
        Schema::create('reports', function (Blueprint $table) {
            $table->integer('id', true);
            $table->integer('user_id');
            $table->integer('product_id');
            $table->text('title')->nullable();
            $table->text('note')->nullable();
            $table->timestamps();
        });
        Schema::create('review_translations', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('review_id');
            $table->string('locale')->index();
            $table->string('title');
            $table->string('subtitle');
            $table->text('details');
            $table->unique(['review_id', 'locale']);
        });
        Schema::create('reviews', function (Blueprint $table) {
            $table->increments('id');
            $table->string('photo', 191)->nullable();
        });
        Schema::create('role_translations', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('role_id');
            $table->string('locale')->index();
            $table->string('name');
            $table->unique(['role_id', 'locale']);
        });
        Schema::create('roles', function (Blueprint $table) {
            $table->integer('id', true);
            $table->text('section')->nullable();
        });
        Schema::create('seotool_translations', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('seotool_id');
            $table->string('locale')->index();
            $table->text('meta_keys')->nullable();
            $table->text('meta_description')->nullable();
            $table->unique(['seotool_id', 'locale']);
        });
        Schema::create('seotools', function (Blueprint $table) {
            $table->increments('id');
            $table->text('google_analytics')->nullable();
            $table->text('facebook_pixel')->nullable();
            $table->text('tag_manager_head')->nullable();
            $table->text('tag_manager_body')->nullable();
        });
        Schema::create('service_translations', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('service_id');
            $table->string('locale')->index();
            $table->string('title');
            $table->text('details');
            $table->unique(['service_id', 'locale']);
        });
        Schema::create('services', function (Blueprint $table) {
            $table->integer('id', true);
            $table->integer('user_id')->default(0);
            $table->string('photo', 191);
        });
        Schema::create('shipping_prices', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('shipping_id')->index('shippping_prices_shipping_id_index');
            $table->integer('city_id')->nullable()->index('shippping_prices_city_id_index');
            $table->integer('state_id')->nullable();
            $table->integer('country_id')->nullable();
            $table->double('price')->nullable();
            $table->double('price_per_kilo')->nullable();
            $table->double('price_free_shipping')->nullable();
            $table->integer('status');
        });
        Schema::create('shipping_prices_translations', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('shipping_prices_id');
            $table->string('locale')->index();
            $table->string('delivery_time')->nullable();
            $table->unique(['shipping_prices_id', 'locale']);
        });
        Schema::create('shipping_translations', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('shipping_id');
            $table->string('locale')->index();
            $table->string('title');
            $table->string('subtitle')->nullable();
            $table->string('delivery_time')->nullable();
            $table->unique(['shipping_id', 'locale']);
        });
        Schema::create('shippings', function (Blueprint $table) {
            $table->integer('id', true);
            $table->integer('user_id')->default(0);
            $table->double('price')->nullable();
            $table->enum('shipping_type', ['Free', 'Fixed Price', 'Fixed Weight', 'Free Location', 'Fixed Price Location', 'Fixed Weight Location']);
            $table->double('price_free_shipping')->nullable();
            $table->double('price_per_kilo')->nullable();
            $table->integer('status');
            $table->tinyInteger('local_shipping');
            $table->unsignedInteger('city_id')->nullable()->index('shippings_city_id_foreign');
            $table->unsignedInteger('state_id')->nullable()->index('shippings_state_id_foreign');
            $table->integer('country_id')->nullable()->index('shippings_country_id_foreign');
        });
        Schema::create('slider_store', function (Blueprint $table) {
            $table->unsignedInteger('slider_id')->index();
            $table->unsignedInteger('store_id')->index();
        });
        Schema::create('slider_translations', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('slider_id');
            $table->string('locale')->index();
            $table->text('subtitle_text')->nullable();
            $table->text('title_text')->nullable();
            $table->text('details_text')->nullable();
            $table->unique(['slider_id', 'locale']);
        });
        Schema::create('sliders', function (Blueprint $table) {
            $table->increments('id');
            $table->string('subtitle_size', 50)->nullable();
            $table->string('subtitle_color', 50)->nullable();
            $table->string('subtitle_anime', 50)->nullable();
            $table->string('title_size', 50)->nullable();
            $table->string('title_color', 50)->nullable();
            $table->string('title_anime', 50)->nullable();
            $table->string('details_size', 50)->nullable();
            $table->string('details_color', 50)->nullable();
            $table->string('details_anime', 50)->nullable();
            $table->string('photo', 191)->nullable();
            $table->string('position', 50)->nullable();
            $table->text('link')->nullable();
        });
        Schema::create('social_providers', function (Blueprint $table) {
            $table->integer('id', true);
            $table->integer('user_id');
            $table->string('provider_id', 191);
            $table->string('provider', 191);
            $table->timestamps();
        });
        Schema::create('socialsettings', function (Blueprint $table) {
            $table->increments('id');
            $table->string('facebook', 191);
            $table->string('gplus', 191);
            $table->string('twitter', 191);
            $table->string('linkedin', 191);
            $table->string('dribble', 191)->nullable();
            $table->string('instagram')->nullable();
            $table->tinyInteger('f_status')->default(1);
            $table->tinyInteger('g_status')->default(1);
            $table->tinyInteger('t_status')->default(1);
            $table->tinyInteger('l_status')->default(1);
            $table->tinyInteger('d_status')->default(1);
            $table->tinyInteger('i_status')->default(0);
            $table->tinyInteger('f_check')->nullable();
            $table->tinyInteger('g_check')->nullable();
            $table->text('fclient_id')->nullable();
            $table->text('fclient_secret')->nullable();
            $table->text('fredirect')->nullable();
            $table->text('gclient_id')->nullable();
            $table->text('gclient_secret')->nullable();
            $table->text('gredirect')->nullable();
        });
        Schema::create('states', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->integer('country_id')->index();
            $table->char('initial', 2)->nullable();
        });
        Schema::create('subcategories', function (Blueprint $table) {
            $table->integer('id', true);
            $table->integer('category_id')->index();
            $table->string('slug', 191);
            $table->tinyInteger('status')->default(1);
        });
        Schema::create('subcategory_translations', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('subcategory_id');
            $table->string('locale')->index();
            $table->string('name');
            $table->unique(['subcategory_id', 'locale']);
        });
        Schema::create('subscribers', function (Blueprint $table) {
            $table->integer('id', true);
            $table->string('email', 191);
        });
        Schema::create('subscription_translations', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('subscription_id');
            $table->string('locale')->index();
            $table->string('title');
            $table->text('details')->nullable();
            $table->unique(['subscription_id', 'locale']);
        });
        Schema::create('subscriptions', function (Blueprint $table) {
            $table->integer('id', true);
            $table->string('currency', 50);
            $table->string('currency_code', 50);
            $table->double('price')->default(0);
            $table->integer('days');
            $table->integer('allowed_products')->default(0);
        });
        Schema::create('team_member_categories', function (Blueprint $table) {
            $table->increments('id');
        });
        Schema::create('team_member_category_translations', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('team_member_category_id');
            $table->string('locale')->index();
            $table->string('name');
            $table->unique(['team_member_category_id', 'locale'], 'team_category_index_unique');
        });
        Schema::create('team_members', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('category_id')->index('salespeoples_category_id_index');
            $table->string('name');
            $table->string('photo')->nullable();
            $table->string('whatsapp')->nullable();
            $table->string('skype')->nullable();
            $table->string('email')->nullable();
        });
        Schema::create('user_notifications', function (Blueprint $table) {
            $table->integer('id', true);
            $table->integer('user_id');
            $table->text('order_number');
            $table->tinyInteger('is_read')->default(0);
            $table->timestamps();
        });
        Schema::create('user_subscriptions', function (Blueprint $table) {
            $table->integer('id', true);
            $table->integer('user_id');
            $table->integer('subscription_id');
            $table->text('title');
            $table->string('currency', 50);
            $table->string('currency_code', 50);
            $table->double('price')->default(0);
            $table->integer('days');
            $table->integer('allowed_products')->default(0);
            $table->text('details')->nullable();
            $table->string('method', 50)->default('Free');
            $table->string('txnid')->nullable();
            $table->string('charge_id')->nullable();
            $table->timestamps();
            $table->integer('status')->default(0);
            $table->text('payment_number')->nullable();
        });
        Schema::create('user_translations', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('user_id');
            $table->string('locale')->index();
            $table->text('shop_details')->nullable();
            $table->text('shop_message')->nullable();
            $table->unique(['user_id', 'locale']);
        });
        Schema::create('users', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name', 191);
            $table->string('document')->nullable();
            $table->string('photo', 191)->nullable();
            $table->string('zip', 191)->nullable();
            $table->string('city', 191)->nullable();
            $table->string('state')->nullable();
            $table->string('country', 100)->nullable();
            $table->string('address', 191)->nullable();
            $table->string('address_number')->nullable();
            $table->string('complement')->nullable();
            $table->string('district')->nullable();
            $table->string('phone', 191)->nullable();
            $table->string('fax', 191)->nullable();
            $table->string('email', 191)->unique();
            $table->string('password', 191)->nullable();
            $table->string('password_reset')->nullable();
            $table->rememberToken();
            $table->timestamps();
            $table->tinyInteger('is_provider')->default(0);
            $table->tinyInteger('status')->default(0);
            $table->text('verification_link')->nullable();
            $table->enum('email_verified', ['Yes', 'No'])->default('No');
            $table->text('affilate_code')->nullable();
            $table->double('affilate_income')->default(0);
            $table->text('shop_name')->nullable();
            $table->text('owner_name')->nullable();
            $table->text('shop_number')->nullable();
            $table->text('shop_address')->nullable();
            $table->text('reg_number')->nullable();
            $table->string('shop_image', 191)->nullable();
            $table->text('f_url')->nullable();
            $table->text('g_url')->nullable();
            $table->text('t_url')->nullable();
            $table->text('l_url')->nullable();
            $table->tinyInteger('is_vendor')->default(0);
            $table->tinyInteger('f_check')->default(0);
            $table->tinyInteger('g_check')->default(0);
            $table->tinyInteger('t_check')->default(0);
            $table->tinyInteger('l_check')->default(0);
            $table->tinyInteger('mail_sent')->default(0);
            $table->double('shipping_cost')->default(0);
            $table->double('current_balance')->default(0);
            $table->date('date')->nullable();
            $table->tinyInteger('ban')->default(0);
            $table->unsignedInteger('city_id')->nullable()->index('users_city_id_foreign');
            $table->unsignedInteger('state_id')->nullable()->index('users_state_id_foreign');
            $table->integer('country_id')->nullable()->index('users_country_id_foreign');
        });
        Schema::create('vendor_orders', function (Blueprint $table) {
            $table->integer('id', true);
            $table->integer('user_id');
            $table->integer('order_id');
            $table->integer('qty');
            $table->double('price');
            $table->string('order_number', 191);
            $table->enum('status', ['pending', 'processing', 'completed', 'declined', 'on delivery'])->default('pending');
        });
        Schema::create('verification_translations', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('verification_id');
            $table->string('locale')->index();
            $table->text('text')->nullable();
            $table->text('warning_reason')->nullable();
            $table->unique(['verification_id', 'locale']);
        });
        Schema::create('verifications', function (Blueprint $table) {
            $table->integer('id', true);
            $table->integer('user_id');
            $table->text('attachments')->nullable();
            $table->enum('status', ['Pending', 'Verified', 'Declined'])->nullable();
            $table->tinyInteger('admin_warning')->default(0);
            $table->timestamps();
        });
        Schema::create('wishlists', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('user_id');
            $table->unsignedInteger('product_id');
        });
        Schema::create('withdraws', function (Blueprint $table) {
            $table->integer('id', true);
            $table->integer('user_id')->nullable();
            $table->string('method')->nullable();
            $table->string('acc_email')->nullable();
            $table->string('iban')->nullable();
            $table->string('country')->nullable();
            $table->string('acc_name')->nullable();
            $table->text('address')->nullable();
            $table->string('swift')->nullable();
            $table->text('reference')->nullable();
            $table->float('amount', 10, 0)->nullable();
            $table->float('fee', 10, 0)->nullable()->default(0);
            $table->timestamps();
            $table->enum('status', ['pending', 'completed', 'rejected'])->default('pending');
            $table->enum('type', ['user', 'vendor']);
        });
        Schema::table('attribute_option_translations', function (Blueprint $table) {
            $table->foreign('attribute_option_id')->references('id')->on('attribute_options')->onUpdate('RESTRICT')->onDelete('CASCADE');
        });
        Schema::table('attribute_options', function (Blueprint $table) {
            $table->foreign('attribute_id')->references('id')->on('attributes')->onUpdate('RESTRICT')->onDelete('CASCADE');
        });
        Schema::table('attribute_translations', function (Blueprint $table) {
            $table->foreign('attribute_id')->references('id')->on('attributes')->onUpdate('RESTRICT')->onDelete('CASCADE');
        });
        Schema::table('blog_category_translations', function (Blueprint $table) {
            $table->foreign('blog_category_id')->references('id')->on('blog_categories')->onUpdate('RESTRICT')->onDelete('CASCADE');
        });
        Schema::table('blog_translations', function (Blueprint $table) {
            $table->foreign('blog_id')->references('id')->on('blogs')->onUpdate('RESTRICT')->onDelete('CASCADE');
        });
        Schema::table('category_translations', function (Blueprint $table) {
            $table->foreign('category_id')->references('id')->on('categories')->onUpdate('RESTRICT')->onDelete('CASCADE');
        });
        Schema::table('childcategories', function (Blueprint $table) {
            $table->foreign('category_id')->references('id')->on('categories')->onUpdate('RESTRICT')->onDelete('SET NULL');
            $table->foreign('subcategory_id')->references('id')->on('subcategories')->onUpdate('RESTRICT')->onDelete('CASCADE');
        });
        Schema::table('childcategory_translations', function (Blueprint $table) {
            $table->foreign('childcategory_id')->references('id')->on('childcategories')->onUpdate('RESTRICT')->onDelete('CASCADE');
        });
        Schema::table('cities', function (Blueprint $table) {
            $table->foreign('state_id')->references('id')->on('states')->onUpdate('RESTRICT')->onDelete('CASCADE');
        });
        Schema::table('email_template_translations', function (Blueprint $table) {
            $table->foreign('email_template_id')->references('id')->on('email_templates')->onUpdate('RESTRICT')->onDelete('CASCADE');
        });
        Schema::table('faq_translations', function (Blueprint $table) {
            $table->foreign('faq_id')->references('id')->on('faqs')->onUpdate('RESTRICT')->onDelete('CASCADE');
        });
        Schema::table('generalsetting_translations', function (Blueprint $table) {
            $table->foreign('generalsetting_id')->references('id')->on('generalsettings')->onUpdate('RESTRICT')->onDelete('CASCADE');
        });
        Schema::table('order_track_translations', function (Blueprint $table) {
            $table->foreign('order_track_id')->references('id')->on('order_tracks')->onUpdate('RESTRICT')->onDelete('CASCADE');
        });
        Schema::table('package_translations', function (Blueprint $table) {
            $table->foreign('package_id')->references('id')->on('packages')->onUpdate('RESTRICT')->onDelete('CASCADE');
        });
        Schema::table('page_translations', function (Blueprint $table) {
            $table->foreign('page_id')->references('id')->on('pages')->onUpdate('RESTRICT')->onDelete('CASCADE');
        });
        Schema::table('pagesetting_translations', function (Blueprint $table) {
            $table->foreign('pagesetting_id')->references('id')->on('pagesettings')->onUpdate('RESTRICT')->onDelete('CASCADE');
        });
        Schema::table('pagesettings', function (Blueprint $table) {
            $table->foreign('store_id')->references('id')->on('generalsettings')->onUpdate('RESTRICT')->onDelete('CASCADE');
        });
        Schema::table('pickup_translations', function (Blueprint $table) {
            $table->foreign('pickup_id')->references('id')->on('pickups')->onUpdate('RESTRICT')->onDelete('CASCADE');
        });
        Schema::table('product_clicks', function (Blueprint $table) {
            $table->foreign('product_id')->references('id')->on('products')->onUpdate('RESTRICT')->onDelete('RESTRICT');
        });
        Schema::table('product_translations', function (Blueprint $table) {
            $table->foreign('product_id')->references('id')->on('products')->onUpdate('RESTRICT')->onDelete('CASCADE');
        });
        Schema::table('products', function (Blueprint $table) {
            $table->foreign('brand_id')->references('id')->on('brands')->onUpdate('RESTRICT')->onDelete('SET NULL');
            $table->foreign('category_id')->references('id')->on('categories')->onUpdate('RESTRICT')->onDelete('SET NULL');
            $table->foreign('childcategory_id')->references('id')->on('childcategories')->onUpdate('RESTRICT')->onDelete('SET NULL');
            $table->foreign('subcategory_id')->references('id')->on('subcategories')->onUpdate('RESTRICT')->onDelete('SET NULL');
        });
        Schema::table('review_translations', function (Blueprint $table) {
            $table->foreign('review_id')->references('id')->on('reviews')->onUpdate('RESTRICT')->onDelete('CASCADE');
        });
        Schema::table('role_translations', function (Blueprint $table) {
            $table->foreign('role_id')->references('id')->on('roles')->onUpdate('RESTRICT')->onDelete('CASCADE');
        });
        Schema::table('seotool_translations', function (Blueprint $table) {
            $table->foreign('seotool_id')->references('id')->on('seotools')->onUpdate('RESTRICT')->onDelete('CASCADE');
        });
        Schema::table('service_translations', function (Blueprint $table) {
            $table->foreign('service_id')->references('id')->on('services')->onUpdate('RESTRICT')->onDelete('CASCADE');
        });
        Schema::table('shipping_prices', function (Blueprint $table) {
            $table->foreign('shipping_id', 'shippping_prices_shipping_id_foreign')->references('id')->on('shippings')->onUpdate('RESTRICT')->onDelete('RESTRICT');
        });
        Schema::table('shipping_prices_translations', function (Blueprint $table) {
            $table->foreign('shipping_prices_id')->references('id')->on('shipping_prices')->onUpdate('RESTRICT')->onDelete('CASCADE');
        });
        Schema::table('shipping_translations', function (Blueprint $table) {
            $table->foreign('shipping_id')->references('id')->on('shippings')->onUpdate('RESTRICT')->onDelete('CASCADE');
        });
        Schema::table('shippings', function (Blueprint $table) {
            $table->foreign('city_id')->references('id')->on('cities')->onUpdate('RESTRICT')->onDelete('CASCADE');
            $table->foreign('country_id')->references('id')->on('countries')->onUpdate('RESTRICT')->onDelete('CASCADE');
            $table->foreign('state_id')->references('id')->on('states')->onUpdate('RESTRICT')->onDelete('CASCADE');
        });
        Schema::table('slider_translations', function (Blueprint $table) {
            $table->foreign('slider_id')->references('id')->on('sliders')->onUpdate('RESTRICT')->onDelete('CASCADE');
        });
        Schema::table('states', function (Blueprint $table) {
            $table->foreign('country_id')->references('id')->on('countries')->onUpdate('RESTRICT')->onDelete('CASCADE');
        });
        Schema::table('subcategories', function (Blueprint $table) {
            $table->foreign('category_id')->references('id')->on('categories')->onUpdate('RESTRICT')->onDelete('CASCADE');
        });
        Schema::table('subcategory_translations', function (Blueprint $table) {
            $table->foreign('subcategory_id')->references('id')->on('subcategories')->onUpdate('RESTRICT')->onDelete('CASCADE');
        });
        Schema::table('subscription_translations', function (Blueprint $table) {
            $table->foreign('subscription_id')->references('id')->on('subscriptions')->onUpdate('RESTRICT')->onDelete('CASCADE');
        });
        Schema::table('team_member_category_translations', function (Blueprint $table) {
            $table->foreign('team_member_category_id', 'team_category_id_foreign')->references('id')->on('team_member_categories')->onUpdate('RESTRICT')->onDelete('CASCADE');
        });
        Schema::table('team_members', function (Blueprint $table) {
            $table->foreign('category_id')->references('id')->on('team_member_categories')->onUpdate('RESTRICT')->onDelete('RESTRICT');
        });
        Schema::table('user_translations', function (Blueprint $table) {
            $table->foreign('user_id')->references('id')->on('users')->onUpdate('RESTRICT')->onDelete('CASCADE');
        });
        Schema::table('users', function (Blueprint $table) {
            $table->foreign('city_id')->references('id')->on('cities')->onUpdate('RESTRICT')->onDelete('SET NULL');
            $table->foreign('country_id')->references('id')->on('countries')->onUpdate('RESTRICT')->onDelete('SET NULL');
            $table->foreign('state_id')->references('id')->on('states')->onUpdate('RESTRICT')->onDelete('SET NULL');
        });
        Schema::table('verification_translations', function (Blueprint $table) {
            $table->foreign('verification_id')->references('id')->on('verifications')->onUpdate('RESTRICT')->onDelete('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('attribute_option_translations', function (Blueprint $table) {
            $table->dropForeign('attribute_option_translations_attribute_option_id_foreign');
        });
        Schema::table('attribute_options', function (Blueprint $table) {
            $table->dropForeign('attribute_options_attribute_id_foreign');
        });
        Schema::table('attribute_translations', function (Blueprint $table) {
            $table->dropForeign('attribute_translations_attribute_id_foreign');
        });
        Schema::table('blog_category_translations', function (Blueprint $table) {
            $table->dropForeign('blog_category_translations_blog_category_id_foreign');
        });
        Schema::table('blog_translations', function (Blueprint $table) {
            $table->dropForeign('blog_translations_blog_id_foreign');
        });
        Schema::table('category_translations', function (Blueprint $table) {
            $table->dropForeign('category_translations_category_id_foreign');
        });
        Schema::table('childcategories', function (Blueprint $table) {
            $table->dropForeign('childcategories_category_id_foreign');
            $table->dropForeign('childcategories_subcategory_id_foreign');
        });
        Schema::table('childcategory_translations', function (Blueprint $table) {
            $table->dropForeign('childcategory_translations_childcategory_id_foreign');
        });
        Schema::table('cities', function (Blueprint $table) {
            $table->dropForeign('cities_state_id_foreign');
        });
        Schema::table('email_template_translations', function (Blueprint $table) {
            $table->dropForeign('email_template_translations_email_template_id_foreign');
        });
        Schema::table('faq_translations', function (Blueprint $table) {
            $table->dropForeign('faq_translations_faq_id_foreign');
        });
        Schema::table('generalsetting_translations', function (Blueprint $table) {
            $table->dropForeign('generalsetting_translations_generalsetting_id_foreign');
        });
        Schema::table('order_track_translations', function (Blueprint $table) {
            $table->dropForeign('order_track_translations_order_track_id_foreign');
        });
        Schema::table('package_translations', function (Blueprint $table) {
            $table->dropForeign('package_translations_package_id_foreign');
        });
        Schema::table('page_translations', function (Blueprint $table) {
            $table->dropForeign('page_translations_page_id_foreign');
        });
        Schema::table('pagesetting_translations', function (Blueprint $table) {
            $table->dropForeign('pagesetting_translations_pagesetting_id_foreign');
        });
        Schema::table('pagesettings', function (Blueprint $table) {
            $table->dropForeign('pagesettings_store_id_foreign');
        });
        Schema::table('pickup_translations', function (Blueprint $table) {
            $table->dropForeign('pickup_translations_pickup_id_foreign');
        });
        Schema::table('product_clicks', function (Blueprint $table) {
            $table->dropForeign('product_clicks_product_id_foreign');
        });
        Schema::table('product_translations', function (Blueprint $table) {
            $table->dropForeign('product_translations_product_id_foreign');
        });
        Schema::table('products', function (Blueprint $table) {
            $table->dropForeign('products_brand_id_foreign');
            $table->dropForeign('products_category_id_foreign');
            $table->dropForeign('products_childcategory_id_foreign');
            $table->dropForeign('products_subcategory_id_foreign');
        });
        Schema::table('review_translations', function (Blueprint $table) {
            $table->dropForeign('review_translations_review_id_foreign');
        });
        Schema::table('role_translations', function (Blueprint $table) {
            $table->dropForeign('role_translations_role_id_foreign');
        });
        Schema::table('seotool_translations', function (Blueprint $table) {
            $table->dropForeign('seotool_translations_seotool_id_foreign');
        });
        Schema::table('service_translations', function (Blueprint $table) {
            $table->dropForeign('service_translations_service_id_foreign');
        });
        Schema::table('shipping_prices', function (Blueprint $table) {
            $table->dropForeign('shippping_prices_shipping_id_foreign');
        });
        Schema::table('shipping_prices_translations', function (Blueprint $table) {
            $table->dropForeign('shipping_prices_translations_shipping_prices_id_foreign');
        });
        Schema::table('shipping_translations', function (Blueprint $table) {
            $table->dropForeign('shipping_translations_shipping_id_foreign');
        });
        Schema::table('shippings', function (Blueprint $table) {
            $table->dropForeign('shippings_city_id_foreign');
            $table->dropForeign('shippings_country_id_foreign');
            $table->dropForeign('shippings_state_id_foreign');
        });
        Schema::table('slider_translations', function (Blueprint $table) {
            $table->dropForeign('slider_translations_slider_id_foreign');
        });
        Schema::table('states', function (Blueprint $table) {
            $table->dropForeign('states_country_id_foreign');
        });
        Schema::table('subcategories', function (Blueprint $table) {
            $table->dropForeign('subcategories_category_id_foreign');
        });
        Schema::table('subcategory_translations', function (Blueprint $table) {
            $table->dropForeign('subcategory_translations_subcategory_id_foreign');
        });
        Schema::table('subscription_translations', function (Blueprint $table) {
            $table->dropForeign('subscription_translations_subscription_id_foreign');
        });
        Schema::table('team_member_category_translations', function (Blueprint $table) {
            $table->dropForeign('team_category_id_foreign');
        });
        Schema::table('team_members', function (Blueprint $table) {
            $table->dropForeign('team_members_category_id_foreign');
        });
        Schema::table('user_translations', function (Blueprint $table) {
            $table->dropForeign('user_translations_user_id_foreign');
        });
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign('users_city_id_foreign');
            $table->dropForeign('users_country_id_foreign');
            $table->dropForeign('users_state_id_foreign');
        });
        Schema::table('verification_translations', function (Blueprint $table) {
            $table->dropForeign('verification_translations_verification_id_foreign');
        });
        Schema::dropIfExists('generalsettings');
        Schema::dropIfExists('languages');
        Schema::dropIfExists('currencies');
        Schema::dropIfExists('activity_log');
        Schema::dropIfExists('admin_languages');
        Schema::dropIfExists('admin_user_conversations');
        Schema::dropIfExists('admin_user_messages');
        Schema::dropIfExists('admins');
        Schema::dropIfExists('attribute_option_translations');
        Schema::dropIfExists('attribute_options');
        Schema::dropIfExists('attribute_translations');
        Schema::dropIfExists('attributes');
        Schema::dropIfExists('bank_accounts');
        Schema::dropIfExists('banner_store');
        Schema::dropIfExists('banners');
        Schema::dropIfExists('blog_categories');
        Schema::dropIfExists('blog_category_translations');
        Schema::dropIfExists('blog_translations');
        Schema::dropIfExists('blogs');
        Schema::dropIfExists('brands');
        Schema::dropIfExists('categories');
        Schema::dropIfExists('category_translations');
        Schema::dropIfExists('childcategories');
        Schema::dropIfExists('childcategory_translations');
        Schema::dropIfExists('cities');
        Schema::dropIfExists('comments');
        Schema::dropIfExists('conversations');
        Schema::dropIfExists('counters');
        Schema::dropIfExists('countries');
        Schema::dropIfExists('coupons');
        Schema::dropIfExists('email_template_translations');
        Schema::dropIfExists('email_templates');
        Schema::dropIfExists('failed_jobs');
        Schema::dropIfExists('faq_translations');
        Schema::dropIfExists('faqs');
        Schema::dropIfExists('favorite_sellers');
        Schema::dropIfExists('galleries');
        Schema::dropIfExists('galleries360');
        Schema::dropIfExists('generalsetting_translations');
        Schema::dropIfExists('jobs');
        Schema::dropIfExists('messages');
        Schema::dropIfExists('notifications');
        Schema::dropIfExists('order_track_translations');
        Schema::dropIfExists('order_tracks');
        Schema::dropIfExists('orders');
        Schema::dropIfExists('package_translations');
        Schema::dropIfExists('packages');
        Schema::dropIfExists('page_translations');
        Schema::dropIfExists('pages');
        Schema::dropIfExists('pagesetting_translations');
        Schema::dropIfExists('pagesettings');
        Schema::dropIfExists('partners');
        Schema::dropIfExists('payment_gateways');
        Schema::dropIfExists('pickup_translations');
        Schema::dropIfExists('pickups');
        Schema::dropIfExists('product_clicks');
        Schema::dropIfExists('product_store');
        Schema::dropIfExists('product_translations');
        Schema::dropIfExists('products');
        Schema::dropIfExists('ratings');
        Schema::dropIfExists('replies');
        Schema::dropIfExists('reports');
        Schema::dropIfExists('review_translations');
        Schema::dropIfExists('reviews');
        Schema::dropIfExists('role_translations');
        Schema::dropIfExists('roles');
        Schema::dropIfExists('seotool_translations');
        Schema::dropIfExists('seotools');
        Schema::dropIfExists('service_translations');
        Schema::dropIfExists('services');
        Schema::dropIfExists('shipping_prices');
        Schema::dropIfExists('shipping_prices_translations');
        Schema::dropIfExists('shipping_translations');
        Schema::dropIfExists('shippings');
        Schema::dropIfExists('slider_store');
        Schema::dropIfExists('slider_translations');
        Schema::dropIfExists('sliders');
        Schema::dropIfExists('social_providers');
        Schema::dropIfExists('socialsettings');
        Schema::dropIfExists('states');
        Schema::dropIfExists('subcategories');
        Schema::dropIfExists('subcategory_translations');
        Schema::dropIfExists('subscribers');
        Schema::dropIfExists('subscription_translations');
        Schema::dropIfExists('subscriptions');
        Schema::dropIfExists('team_member_categories');
        Schema::dropIfExists('team_member_category_translations');
        Schema::dropIfExists('team_members');
        Schema::dropIfExists('user_notifications');
        Schema::dropIfExists('user_subscriptions');
        Schema::dropIfExists('user_translations');
        Schema::dropIfExists('users');
        Schema::dropIfExists('vendor_orders');
        Schema::dropIfExists('verification_translations');
        Schema::dropIfExists('verifications');
        Schema::dropIfExists('wishlists');
        Schema::dropIfExists('withdraws');
    }
}

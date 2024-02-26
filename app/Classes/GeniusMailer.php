<?php

namespace App\Classes;

use App\Jobs\AdminEmail as JobsAdminEmail;
use App\Mail\AdminEmail;
use App\Models\BankAccount;
use App\Models\Order;
use App\Models\Currency;
use App\Models\EmailTemplate;
use App\Models\Generalsetting;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use App\Models\CartAbandonment;

class GeniusMailer
{
    private $storeSettings;

    public function __construct()
    {
        $this->storeSettings = resolve('storeSettings');
    }


    public function sendAutoOrderMail(array $mailData, $id)
    {
        $temp = EmailTemplate::where('email_type', '=', $mailData['type'])->first();

        $body = preg_replace("/{customer_name}/", $mailData['cname'], $temp->email_body);
        $body = preg_replace("/{order_amount}/", $mailData['oamount'], $body);
        $body = preg_replace("/{admin_name}/", $mailData['aname'], $body);
        $body = preg_replace("/{admin_email}/", $mailData['aemail'], $body);
        $body = preg_replace("/{order_number}/", $mailData['onumber'], $body);
        $body = preg_replace("/{website_title}/", $this->storeSettings->title, $body);

        $objDemo = new \stdClass();
        $objDemo->to = $mailData['to'];
        $objDemo->subject = $temp->email_subject;
        $objDemo->from_email = $mailData['from_email'] ?? $this->storeSettings->from_email;
        $objDemo->from_name = $mailData['from_name'] ?? $this->storeSettings->from_name;
        $objDemo->reply = $mailData['reply'] ?? $this->storeSettings->from_email;

        try {
            $order = Order::findOrFail($id);
            $cart = $order->cart;
            $first_curr = Currency::where('id', '=', 1)->first();
            $order_curr = Currency::where('sign', '=', $order->currency_sign)->first();
            $bankAccounts =  BankAccount::where('status', '=', 1)->get();

            if (empty($order_curr)) {
                $order_curr = $first_curr;
            }

            $orderData = [
                "view" => "print.order",
                "order" => $order,
                "cart" => $cart,
                "first_curr" => $first_curr,
                "order_curr" => $order_curr,
                "bank_accounts" => $bankAccounts,
                'to_email' => $objDemo->to,
                'from_email' => $objDemo->from_email,
                'from_name' => $objDemo->from_name,
                'reply' => $objDemo->reply
            ];

            Mail::to($objDemo->to)->queue(new AdminEmail($body, $objDemo->subject, $orderData));
        } catch (\Exception $e) {
            Log::debug('genius_mailer_auto_order', [$e->getMessage()]);
        }
    }

    public function sendAutoMail(array $mailData)
    {
        $temp = EmailTemplate::where('email_type', '=', $mailData['type'])->first();

        $body = preg_replace("/{customer_name}/", $mailData['cname'], $temp->email_body);
        $body = preg_replace("/{order_amount}/", $mailData['oamount'], $body);
        $body = preg_replace("/{admin_name}/", $mailData['aname'], $body);
        $body = preg_replace("/{admin_email}/", $mailData['aemail'], $body);
        $body = preg_replace("/{order_number}/", $mailData['onumber'], $body);
        $body = preg_replace("/{website_title}/", $this->storeSettings->title, $body);

        if ($this->storeSettings->is_back_in_stock && array_key_exists('product', $mailData)) {
            $body = preg_replace("/{product}/", $mailData['product'], $body);
        }

        $objDemo = new \stdClass();
        $objDemo->to = $mailData['to'];
        $objDemo->subject = $temp->email_subject;
        $objDemo->from_email = $mailData['from_email'] ?? $this->storeSettings->from_email;
        $objDemo->from_name = $mailData['from_name'] ?? $this->storeSettings->from_name;
        $objDemo->reply = $mailData['reply'] ?? $this->storeSettings->from_email;

        try {
            Mail::to($objDemo->to)->queue(new AdminEmail($body, $objDemo->subject, ['to_email' => $objDemo->to, 'from_email' => $objDemo->from_email, 'from_name' => $objDemo->from_name, 'reply' => $objDemo->reply]));
        } catch (\Exception $e) {
            Log::debug('genius_mailer_auto_mail', [$e->getMessage()]);
        }
    }

    public function sendAbandonMail(array $mailData, $id)
    {
        $temp = EmailTemplate::where('email_type', '=', $mailData['type'])->first();

        $body = preg_replace("/{customer_name}/", $mailData['cname'], $temp->email_body);
        $body = preg_replace("/{order_amount}/", $mailData['oamount'], $body);
        $body = preg_replace("/{admin_name}/", $mailData['aname'], $body);
        $body = preg_replace("/{admin_email}/", $mailData['aemail'], $body);
        $body = preg_replace("/{order_number}/", $mailData['onumber'], $body);
        $body = preg_replace("/{website_title}/", $this->storeSettings->title, $body);

        $objDemo = new \stdClass();
        $objDemo->to = $mailData['to'];
        $objDemo->subject = $temp->email_subject;
        $objDemo->from_email = $mailData['from_email'] ?? $this->storeSettings->from_email;
        $objDemo->from_name = $mailData['from_name'] ?? $this->storeSettings->from_name;
        $objDemo->reply = $mailData['reply'] ?? $this->storeSettings->from_email;

        try {
            $ca = CartAbandonment::find($id);
            $cart = $ca->temp_cart;
            $first_curr = Currency::where('id', '=', 1)->first();
            $bankAccounts =  BankAccount::where('status', '=', 1)->get();

            $cartData = [
                "view" => "print.cart",
                "cart" => $cart,
                "first_curr" => $first_curr,
                "order_curr" => $first_curr,
                "bank_accounts" => $bankAccounts,
                'to_email' => $objDemo->to,
                'from_email' => $objDemo->from_email,
                'from_name' => $objDemo->from_name,
                'reply' => $objDemo->reply
            ];

            Mail::to($objDemo->to)->queue(new AdminEmail($body, $objDemo->subject, $cartData));
        } catch (\Exception $e) {
            Log::debug('genius_mailer_auto_mail', [$e->getMessage()]);
        }
    }

    public function sendAdminMail(array $mailData, $id)
    {
        $objDemo = new \stdClass();
        $objDemo->to = $mailData['to'];
        $objDemo->subject = $mailData['subject'];
        $objDemo->from_email = $mailData['from_email'] ?? $this->storeSettings->from_email;
        $objDemo->from_name = $mailData['from_name'] ?? $this->storeSettings->from_name;
        $objDemo->reply = $mailData['reply'] ?? $this->storeSettings->from_email;

        try {
            $order = Order::findOrFail($id);
            $cart = $order->cart;
            $first_curr = Currency::where('id', '=', 1)->first();
            $order_curr = Currency::where('sign', '=', $order->currency_sign)->first();
            $bankAccounts =  BankAccount::where('status', '=', 1)->get();

            if (empty($order_curr)) {
                $order_curr = $first_curr;
            }

            $orderData = [
                "view" => "print.order",
                "order" => $order,
                "cart" => $cart,
                "first_curr" => $first_curr,
                "order_curr" => $order_curr,
                "bank_accounts" => $bankAccounts,
                'to_email' => $objDemo->to,
                'from_email' => $objDemo->from_email,
                'from_name' => $objDemo->from_name,
                'reply' => $objDemo->reply
            ];

            $objDemo->body = $orderData;

            Mail::to($objDemo->to)->queue(new AdminEmail($mailData['body'], $objDemo->subject, $orderData));
        } catch (\Exception $e) {
            Log::debug('genius_mailer_custom', [$e->getMessage()]);
        }

        return true;
    }

    public function sendCustomMail(array $mailData)
    {
        $objDemo = new \stdClass();
        $objDemo->to = $mailData['to'];
        $objDemo->subject = $mailData['subject'];
        $objDemo->from_email = $mailData['from_email'] ?? $this->storeSettings->from_email;
        $objDemo->from_name = $mailData['from_name'] ?? $this->storeSettings->from_name;
        $objDemo->reply = $mailData['reply'] ?? $this->storeSettings->from_email;

        try {
            JobsAdminEmail::dispatch($objDemo, $mailData);
        } catch (\Exception $e) {
            Log::debug('genius_mailer_custom', [$e->getMessage()]);
        }

        return true;
    }
}

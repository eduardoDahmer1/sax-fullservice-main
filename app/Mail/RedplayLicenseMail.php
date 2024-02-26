<?php

namespace App\Mail;

use App\Models\License;
use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class RedplayLicenseMail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * @var Order
     */
    public $order;

    /**
     * @var License
     */
    public $license;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(Order $order, License $license)
    {
        $this->order = $order;
        $this->license = $license;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->markdown('emails.redplay.license');
    }
}

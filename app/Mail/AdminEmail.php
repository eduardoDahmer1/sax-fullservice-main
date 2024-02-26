<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class AdminEmail extends Mailable
{
    use Queueable, SerializesModels;

    /** @var string */
    public $email_body;

    /** @var array */
    public $extraData;

    /** @var string */
    private $title;

    /** @var null|string */
    private $filePath;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($email_body, $title, $extraData = [], $filePath = null)
    {
        $this->email_body = $email_body;
        $this->extraData = $extraData;
        $this->title = $title;
        $this->filePath = $filePath;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $mail = $this->replyTo($this->extraData['reply'])->subject($this->title)->view('admin.email.mailbody');

        if(isset($this->extraData['from_email']) && (!empty($this->extraData['from_email']))) {
            $mail->from($this->extraData['from_email'], $this->extraData['from_name']);
        }

        if($this->filePath) {
            $mail->attach($this->filePath);
        }

        return $mail;
    }
}

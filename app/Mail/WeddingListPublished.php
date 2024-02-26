<?php

namespace App\Mail;

use App\Models\Generalsetting;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Symfony\Component\Mailer\Envelope;

class WeddingListPublished extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public ?int $number;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(public User $user)
    {
        $this->number = Generalsetting::first()->number;
        $this->subject(__('Wedding List') . ' - ' . $user->name);
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('emails.bridal.bridal-list');
    }
}

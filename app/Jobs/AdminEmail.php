<?php

namespace App\Jobs;

use App\Mail\AdminEmail as MailAdminEmail;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;
use stdClass;

class AdminEmail implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(private stdClass $objDemo, private array $mailData)
    {
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        Mail::to($this->objDemo->to)->send(new MailAdminEmail($this->mailData['body'], $this->objDemo->subject, [
            'to_email' => $this->objDemo->to,
            'from_email' => $this->objDemo->from_email, 'from_name' => $this->objDemo->from_name, 'reply' => $this->objDemo->reply
        ]));
    }
}

<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ConfirmationCodeMail extends Mailable
{
    use Queueable, SerializesModels;

    public $confirmationCode;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($confirmationCode)
    {
        $this->confirmationCode = $confirmationCode;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('emails.confirmationCode')
            ->with([
                'confirmationCode' => $this->confirmationCode,
            ]);
    }
}

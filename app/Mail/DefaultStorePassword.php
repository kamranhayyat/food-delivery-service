<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class DefaultStorePassword extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public String $password)
    {
    }

    public function build(): self
    {
        return $this->view('emails.welcome_user');
    }
}

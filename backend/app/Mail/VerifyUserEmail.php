<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class VerifyUserEmail extends Mailable
{
    use Queueable, SerializesModels;

    protected $details;
    /**
     * Create a new message instance.
     */
    public function __construct($details)
    {
        $this->details = $details;
    }

    public function build()
    {
        return $this->subject('Vérification de votre adresse email')
                    ->view('emails.verify')
                    ->with([                   
                        'details' => $this->details,
                    ]);
    }
}

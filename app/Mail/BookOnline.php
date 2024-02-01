<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Auth;

class BookOnline extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */

    public $appointment;
    public $key;
    
    public function __construct($appointment,$key)
    {
        $this->appointment = $appointment;
        $this->key = $key;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $company = $this->appointment->company;
        return $this->markdown('emails.book-online')
                    ->subject('New appointment online')
                    ->replyTo($company->email,$company->name)
                    ->from($company->email,$company->name);
    }
}

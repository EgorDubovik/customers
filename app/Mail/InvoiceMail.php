<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Model\Invoice;
class InvoiceMail extends Mailable
{
    use Queueable, SerializesModels;
    
    public $invoice;
    public $file;
    
    public function __construct($invoice, $file)
    {
        $this->file = $file;
        $this->invoice = $invoice;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->markdown('emails.invoice')
                    ->subject('New invoice')
                    ->attach($this->file,[
                        'as' => $this->invoice->pdf_path,
                        'mime' => 'application/pdf'
                    ])
                    ->from('edservicetx@gmail.com','EDService Appliance repair');
    }
}

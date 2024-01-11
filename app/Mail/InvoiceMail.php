<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Auth;
use App\Model\Invoice;
class InvoiceMail extends Mailable
{
    use Queueable, SerializesModels;
    
    public $invoice;
    public $file;
    public $total = 0;
    public $tax = 0;
    public $due = 0;
    
    public function __construct($invoice, $file)
    {
        $this->file = $file;
        $this->invoice = $invoice;
        $this->setDue();
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
                    ->replyTo(Auth::user()->company->email,Auth::user()->company->name)
                    ->from(Auth::user()->company->email,Auth::user()->company->name);
    }

    private function setDue(){
        $tax = 0;
        $total = 0;
        foreach($this->invoice->appointment->services as $service){
            $total += $service->price;
            if($service->taxable)
                $tax += $service->price * (Auth::user()->settings->tax/100);
        }

        $total += $tax;
        $paid = $this->invoice->appointment->payments->sum('amount');
        $due = $total - $paid;
        $this->due = ($due <= 0) ? '00.00' : number_format($due,2);
        $this->total = number_format($total,2);
        $this->tax = number_format($tax,2);
    }
}

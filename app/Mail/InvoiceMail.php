<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Auth;
use App\Model\Invoice;
use Illuminate\Support\Str;
use App\Models\ReferalLinksCode;
class InvoiceMail extends Mailable
{
    use Queueable, SerializesModels;
    
    public $invoice;
    public $file;
    public $referralCode = null;
    
    public function __construct($invoice, $file)
    {
        $this->file = $file;
        $this->invoice = $invoice;
        // $this->referralCode = $this->getReferralCode();
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->markdown('emails.invoice-v2')
                    ->subject('New invoice')
                    ->attach($this->file,[
                        'as' => $this->invoice->pdf_url,
                        'mime' => 'application/pdf'
                    ])
                    ->replyTo(Auth::user()->company->email,Auth::user()->company->name)
                    ->from(Auth::user()->company->email,Auth::user()->company->name);
    }

    // private function getReferralCode(){

    //     if(!$this->invoice->appointment->customer->referralCode)
    //         $this->invoice->appointment->customer->referralCode = ReferalLinksCode::create([
    //             'company_id' => $this->invoice->appointment->company_id,
    //             'customer_id' => $this->invoice->appointment->customer_id,
    //             'code' => Str::random(10),
    //         ]);

    //     return $this->invoice->appointment->customer->referralCode->code;
    // }
}

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
    public $total = 0;
    public $tax = 0;
    public $due = 0;
    public $subtotal = 0;
    public $referralCode = null;
    
    public function __construct($invoice, $file)
    {
        $this->file = $file;
        $this->invoice = $invoice;
        $this->referralCode = $this->getReferralCode();
        $this->setDue();
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
                        'as' => $this->invoice->pdf_path,
                        'mime' => 'application/pdf'
                    ])
                    ->replyTo(Auth::user()->company->email,Auth::user()->company->name)
                    ->from(Auth::user()->company->email,Auth::user()->company->name);
    }

    private function setDue(){
        $tax = 0;
        $total = 0;
        $subtotal = 0;
        foreach($this->invoice->appointment->services as $service){
            $subtotal += $service->price;
            if($service->taxable)
                $tax += $service->price * (Auth::user()->settings->tax/100);
        }

        $total = $subtotal + $tax;
        $paid = $this->invoice->appointment->payments->sum('amount');
        $due = $total - $paid;
        $this->due = ($due <= 0) ? '00.00' : number_format($due,2);
        $this->total = number_format($total,2);
        $this->tax = number_format($tax,2);
        $this->subtotal = number_format($subtotal,2);
    }

    private function getReferralCode(){

        if(!$this->invoice->appointment->customer->referralCode)
            $this->invoice->appointment->customer->referralCode = ReferalLinksCode::create([
                'company_id' => $this->invoice->appointment->company_id,
                'customer_id' => $this->invoice->appointment->customer_id,
                'code' => Str::random(10),
            ]);

        return $this->invoice->appointment->customer->referralCode->code;

        // $referal = ReferalLinksCode::where('company_id',Auth::user()->company_id)
        //                             ->where('customer_id',$this->invoice->appointment->customer_id)        
        //                             ->first();
        // if($referal){
        //     return $referal->code;
        // } else {
        //     $referalCode = ReferalLinksCode::create([
        //         'company_id' => $this->invoice->appointment->company_id,
        //         'customer_id' => $this->invoice->appointment->customer_id,
        //         'code' => Str::random(10),
        //     ]);
        //     return $referalCode->code;
        // }
    }
}

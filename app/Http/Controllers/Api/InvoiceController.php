<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Appointment;
use App\Models\Payment;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Auth;
use App\Models\Invoice;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use App\Mail\InvoiceMail;
use Illuminate\Support\Str;

class InvoiceController extends Controller
{
    public function index(Request $request){
        
        // !! В слючае удаления апойнтмента, должен остатся, для этого нужно все данные сохронять отдельно а не высчитвать, а именно сумму

        $invoices = Invoice::where('company_id',Auth::user()->company_id)
            ->orderBy('created_at','DESC')
            ->paginate($request->limit ?? 10);
        foreach($invoices as $invoice){
            if($invoice->appointment)
                $invoice->amount = $invoice->appointment->totalPaid();
            else 
                $invoice->amount = 0;
        }
        return response()->json(['invoices' => $invoices], 200);
    }

    public function create(Request $request, $appointment_id){
        $appointment = Appointment::find($appointment_id);
        if(!$appointment)
            return response()->json(['error' => 'Appointment not found'], 404);

        Gate::authorize('create-invoice',['appointment' => $appointment]);
        
        $company = $appointment->company->load('address');
        if(!$company)
            return response()->json(['error' => 'Company not found'], 404);
        $appointment->load('customer','services','address','notes','payments');
        
        list($tax,$subtotal, $total, $paid, $due) = $this->getTaxAndTotal($appointment);

        $appointment->tax = $tax;
        $appointment->subtotal = $subtotal;
        $appointment->due = $due;
        $appointment->total = $total;

        foreach($appointment->payments as $payment){
            $payment->payment_type = Payment::TYPE[$payment->payment_type - 1] ?? 'undefined';
        }

        return response()->json(['appointment'=>$appointment,'company'=>$company], 200);
    }
    public function send(Request $request, $appointment_id){
        $appointment = Appointment::find($appointment_id);
        if(!$appointment)
            return response()->json(['error' => 'Appointment not found'], 404);
        $appointment->load('customer','services','address','notes','payments');

        Gate::authorize('create-invoice',['appointment' => $appointment]);

        $company = $appointment->company->load('address');
        if(!$company)
            return response()->json(['error' => 'Company not found'], 404);
       
        foreach($appointment->payments as $payment){
            $payment->payment_type = Payment::TYPE[$payment->payment_type - 1] ?? 'undefined';
        }

        $invoice = Invoice::create([
            'creator_id'        => Auth::user()->id,
            'company_id'        => Auth::user()->company_id,
            'customer_id'       => $appointment->customer_id,
            'appointment_id'    => $appointment->id,
            'customer_name'     => $appointment->customer->name,
            'address'           => $appointment->address->full,
            'email'             => $appointment->customer->email,
            'status'            => 0,
            'pdf_path'          => null,
        ]);

        $pdfname = $this->createPDF($invoice);
        $invoice->pdf_path = $pdfname;
        $invoice->save();

        $this->sendEmail($invoice);

        return response()->json(['saccsess'=>'saccsess'], 200);
    }

    private function createPDF(Invoice $invoice){
        
        list($tax, $total, $paid, $due) = $this->getTaxAndTotal($invoice->appointment);

        $pdf = PDF::loadView('invoice.PDF',['invoice' => $invoice,'total'=>$total,'due'=>$due,'tax'=>$tax]);
        $content = $pdf->download()->getOriginalContent();
        $filename = (env('APP_DEBUG') ? 'debug-' : "").'Invoice_'.date('m-d-Y').'-'.time().Str::random(50).'.pdf';
        Storage::disk('s3')->put('invoices/'.$filename, $content);
        return $filename;
    }

    private function sendEmail(Invoice $invoice){
        $file = env('AWS_FILE_ACCESS_URL').'invoices/'.$invoice->pdf_path;
        Mail::to($invoice->email)->send(new InvoiceMail($invoice,$file));
    }

    private function getTaxAndTotal(Appointment $appointment){

        $tax = $appointment->totalTax() ?: 0;
        $subtotal = $appointment->totalAmount() ?: 0;
        $total = $subtotal + $tax;
        $paid = $appointment->totalPaid();
        $due = $total - $paid;

        $due = ($due <= 0) ? '00.00' : number_format($due,2);
        $total = number_format($total,2); 
        $tax = number_format($tax,2);
        
        return [$tax, $subtotal ,$total, $paid, $due];
    }
}

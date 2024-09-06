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
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use App\Models\Role;
use App\Models\User;

class InvoiceController extends Controller
{
    public function index(Request $request){
        
        // !! В слючае удаления апойнтмента, должен остатся, для этого нужно все данные сохронять отдельно а не высчитвать, а именно сумму

        /** @var \App\Models\User $user */
        $user = Auth::user();
        $invoices = Invoice::where('company_id',Auth::user()->company_id)
            ->where(function($query) use ($user){
                if(!$user->isRole([Role::ADMIN,Role::DISP]))
                    $query->where('creator_id',Auth::user()->id);
            })
            ->orderBy('created_at','DESC')
            ->paginate($request->limit ?? 10);
        foreach($invoices as $invoice){
            $invoice->creator;
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

        $invoice['id'] = Invoice::count() + 1;
        $invoice['company'] = $appointment->company->makeHidden(['created_at', 'updated_at','address_id','address','id']);
        $invoice['customer'] = $appointment->customer = $appointment->job->customer->makeHidden(['created_at', 'updated_at','id','company_id','address_id']);
        $invoice['address'] = $appointment->job->address->full;
        $invoice['due'] = $appointment->job->remaining_balance;
        $invoice['services'] = $appointment->job->services->makeHidden(['created_at', 'updated_at','job_id','id']);
        $invoice['payments'] = $appointment->job->payments->makeHidden(['updated_at','job_id','id','tech_id','payment_type','company_id']);
        $invoice['total'] = 0;
        $invoice['tax'] = 0;
        foreach($invoice['services'] as $service){
            $invoice['total'] += $service->price;
            // When you change settings modal, you should change this line and add tax to modal as attribute to not calculate it here
            $invoice['tax'] += $service->price * ($service->taxable ? Auth::user()->settings->tax/100 : 0);    
        }
        $invoice['total'] += $invoice['tax'];

        return response()->json(['invoice'=>$invoice], 200);
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

        try{
            DB::beginTransaction();
            $key = Str::random(50);
            $invoice = Invoice::create([
                'creator_id'        => Auth::user()->id,
                'company_id'        => Auth::user()->company_id,
                'customer_id'       => $appointment->customer_id,
                'appointment_id'    => $appointment->id,
                'customer_name'     => $appointment->customer->name,
                'address'           => $appointment->address->full,
                'email'             => $appointment->customer->email,
                'status'            => 0,
                'key'               => $key,
                'pdf_path'          => null,
            ]);
    
            $pdfname = $this->createPDF($invoice);
            $invoice->pdf_path = $pdfname;
            $invoice->save();
    
            $this->sendEmail($invoice);
            DB::commit();
            return response()->json(['saccsess'=>'saccsess'], 200);    
        }
        catch(\Exception $e){
            DB::rollBack();
            return response()->json(['error' => $e->getMessage()], 500);
        }
        
    }

    private function createPDF(Invoice $invoice){
        
        list($tax, $subtotal, $total, $paid, $due) = $this->getTaxAndTotal($invoice->appointment);

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

        $tax = $appointment->totalTax();
        $subtotal = $appointment->totalAmount();
        $total = $subtotal + $tax;
        $paid = $appointment->totalPaid();
        $due = $total - $paid;

        $due = ($due <= 0) ? '00.00' : number_format($due,2);
        $total = number_format($total,2); 
        $tax = number_format($tax,2);
        
        return [$tax, $subtotal ,$total, $paid, $due];
    }

    function download(Request $request, $appointment_id){

        $appointment = Appointment::find($appointment_id);
        if(!$appointment)
            return response()->json(['error' => 'Appointment not found'], 404);

        $this->authorize('create-invoice',['appointment' => $appointment]);

        
        
        $lastInvoice = Invoice::orderBy('id', 'desc')->first();
        $invoice = new Invoice();
        $invoice->id = $lastInvoice->id + 1;
        $invoice->appointment = $appointment;
        $invoice->company = $appointment->company;
        $invoice->customer= $appointment->customer;
        $invoice->address = $appointment->address->full;
        $invoice->email = $appointment->customer->email;
        $invoice->status = 0;

        

        list($tax, $subtotal, $total, $paid, $due) = $this->getTaxAndTotal($appointment);
        
        $pdf = PDF::loadView('invoice.PDF',['invoice' => $invoice,'total'=>$total,'due'=>$due,'tax'=>$tax]);
        
        return $pdf->stream('invoice.pdf');
    }
}

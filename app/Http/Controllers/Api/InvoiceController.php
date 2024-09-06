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
            })->with(['job','job.customer','job.address','creator'])
            ->orderBy('created_at','DESC')
            ->paginate($request->limit ?? 10);
        return response()->json(['invoices' => $invoices], 200);
    }

    public function create(Request $request, $appointment_id){
        $appointment = Appointment::find($appointment_id);
        if(!$appointment)
            return response()->json(['error' => 'Appointment not found'], 404);

        Gate::authorize('create-invoice',['appointment' => $appointment]);

        $invoice = $this->getInvoiceInfo($appointment);

        return response()->json(['invoice'=>$invoice], 200);
    }

    public function send(Request $request, $appointment_id){
        $appointment = Appointment::find($appointment_id);
        if(!$appointment)
            return response()->json(['error' => 'Appointment not found'], 404);

        Gate::authorize('create-invoice',['appointment' => $appointment]);

        try{
            DB::beginTransaction();
            
            // $invoice = $this->getInvoiceInfo($appointment);
            $invoice = new Invoice();
            $invoice->company_id = $appointment->company_id;
            $invoice->creator_id = Auth::user()->id;
            $invoice->job_id = $appointment->job_id; 
            $invoice->email = $appointment->job->customer->email;
            $pdfname = $this->createPDF($invoice);
            $invoice->pdf_path = $pdfname;
            $key = Str::random(50);
            $invoice->key = $key;
            
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
        
        $pdf = PDF::loadView('invoice.PDF',['invoice' => $invoice]);
        $content = $pdf->download()->getOriginalContent();
        $filename = (env('APP_DEBUG') ? 'debug-' : "").'Invoice_'.date('m-d-Y').'-'.time().Str::random(50).'.pdf';
        Storage::disk('s3')->put('invoices/'.$filename, $content);
        return $filename;
    }

    private function sendEmail(Invoice $invoice){
        $file = $invoice->pdf_url;
        Mail::to($invoice->email)->send(new InvoiceMail($invoice,$file));
    }

    function download(Request $request, $appointment_id){

        $appointment = Appointment::find($appointment_id);
        if(!$appointment)
            return response()->json(['error' => 'Appointment not found'], 404);

        $this->authorize('create-invoice',['appointment' => $appointment]);
        
        $invoice = $this->getInvoiceInfo($appointment);
        $pdf = PDF::loadView('invoice.PDF',['invoice' => $invoice]);
        
        return $pdf->stream('invoice.pdf');
    }

    private function getInvoiceInfo($appointment = null, $invoice = null){
        if($appointment){
            $invoice = new Invoice();
            $invoice->id = Invoice::count() + 1;
            $invoice->job = $appointment->job->load(['services','payments','customer','address']);
            $invoice->company = $appointment->company;
            $invoice->created_at = now();
            $invoice->email = $appointment->job->customer->email;
            return $invoice;
        }
        return $invoice;
    }
}

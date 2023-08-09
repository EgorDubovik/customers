<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Invoice;
use App\Mail\InvoiceMail;
use App\Models\Appointment;
use App\Models\InvoiceServices;
use App\Models\Service;
use Dflydev\DotAccessData\Data;
use Illuminate\Support\Facades\Gate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use PDF;


class InvoiceController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $invoices = Invoice::where('company_id',Auth::user()->company_id)->orderBy('created_at','DESC')->get();
        return view("invoice.index",['invoices' => $invoices]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request, Appointment $appointment)
    {
        Gate::authorize('create-invoice',['appointment' => $appointment]);
        $total = $appointment->services->sum('price');
        $paid = $appointment->payments->sum('amount');
        $due = $total - $paid;
        $due = ($due <= 0) ? '00.00' : number_format($due,2);
        $total = number_format($total,2); 
        return view("invoice.create", ['appointment' => $appointment,'total'=>$total,'due' => $due]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $appointment = Appointment::find($request->appointment_id);
        Gate::authorize('create-invoice',['appointment' => $appointment]);
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
        $invoice->key = Str::random(150);
        $invoice->save();

        // send invocie
        $file = storage_path('app/public/pdf/invoices/'.$pdfname);
        Mail::to($invoice->email)->send(new InvoiceMail($invoice,$file));

        return redirect()->route('invoice.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Invoice  $invoice
     * @return \Illuminate\Http\Response
     */
    public function show(Invoice $invoice)
    {
        $this->authorize('can-view-invoice', $invoice);
        $total = $invoice->appointment->services->sum('price');
        $paid = $invoice->appointment->payments->sum('amount');
        $due = $total - $paid;
        $due = ($due <= 0) ? '00.00' : number_format($due,2);
        $total = number_format($total,2); 
        return view('invoice.show',['invoice' => $invoice,'total'=>$total,'due'=>$due]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Invoice  $invoice
     * @return \Illuminate\Http\Response
     */
    public function edit(Invoice $invoice)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Invoice  $invoice
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Invoice $invoice)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Invoice  $invoice
     * @return \Illuminate\Http\Response
     */
    public function destroy(Invoice $invoice)
    {
        //
    }

    private function createPDF(Invoice $invoice){
        
        $total = $invoice->appointment->services->sum('price');
        $paid = $invoice->appointment->payments->sum('amount');
        $due = $total - $paid;
        $due = ($due <= 0) ? '00.00' : number_format($due,2);
        $total = number_format($total,2); 
        $pdf = PDF::loadView('invoice.PDF',['invoice' => $invoice,'total'=>$total,'due'=>$due]);
        $content = $pdf->download()->getOriginalContent();
        $filename = 'Invoice_'.date('m-d-Y').'-'.time().'.pdf';
        Storage::put('public/pdf/invoices/'.$filename,$content);
        return $filename;
    }

    public function viewPDF($key){

        $invoice = Invoice::where('key',$key)->first();
        if(!$invoice)
            abort(404);

        $file = storage_path('app/public/pdf/invoices/'.$invoice->pdf_path);
        if(!file_exists($file))
            abort(404);
        
        return response()->file($file);
    }

    public function resend(Request $request, Invoice $invoice)
    {
        $newInvoice = Invoice::create([
            'creator_id'    => $invoice->creator_id,
            'company_id'    => $invoice->company_id,
            'customer_id'   => $invoice->customer_id,
            'customer_name' => $invoice->customer_name,
            'address' => $invoice->address,
            'email' => $request->email,
            'status' => 0,
            'pdf_path' => null,
        ]);
        
        foreach($invoice->services as $key => $service){
            InvoiceServices::create([
                'invoice_id' => $newInvoice->id,
                'title' => $service->title,
                'description' => $service->description,
                'price' => $service->price,
            ]);
        }
        $pdfname = $this->createPDF($newInvoice);
        $newInvoice->pdf_path = $pdfname;
        $newInvoice->save();

        $file = storage_path('app/public/pdf/invoices/'.$pdfname);
        Mail::to($invoice->email)->send(new InvoiceMail($invoice,$file));

        return redirect()->route('invoice.index');
    }

}

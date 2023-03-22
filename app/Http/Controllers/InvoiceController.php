<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Invoice;
use App\Models\InvoiceServices;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
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
    public function create(Request $request)
    {
        if(!$request->customer_id)
            return view("invoice.create");
        
        $customer = Customer::find($request->customer_id);
        if(!$customer)
            return abort(404);
        
        $this->authorize('can-send-by-customer',$customer);

        return view("invoice.create", ['customer' => $customer]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        
        

        $invoice = Invoice::create([
            'creator_id'    => Auth::user()->id,
            'company_id'    => Auth::user()->company_id,
            'customer_id'   => ($request->customer_id) ? $request->customer_id : null,
            'customer_name' => $request->customer_name,
            'address' => $request->customer_address,
            'email' => $request->email,
            'status' => 0,
            'pdf_path' => null,
        ]);

        
        
        foreach($request->input('service-prices') as $key => $value){
            InvoiceServices::create([
                'invoice_id' => $invoice->id,
                'title' => $request->input('service-title')[$key],
                'description' => $request->input('service-description')[$key],
                'price' => $request->input('service-prices')[$key],
            ]);
        }
        $pdfname = $this->createPDF($invoice);
        $invoice->pdf_path = $pdfname;
        $invoice->save();
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
        $total = $this->getServiceTotal($invoice);
        return view('invoice.show',['invoice' => $invoice,'total' => $total]);
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
        $total = $this->getServiceTotal($invoice);
        $pdf = PDF::loadView('invoice.PDF',['invoice' => $invoice, 'total'=>$total]);
        $content = $pdf->download()->getOriginalContent();
        $filename = 'Invoice_'.date('m-d-Y').'-'.time().'.pdf';
        Storage::put('public/pdf/invoices/'.$filename,$content);
        return $filename;
    }

    public function viewPDF($path){

        $file = storage_path('app/public/pdf/invoices/'.$path);
        if(!file_exists($file))
            abort(404);

        $invoice = Invoice::where('pdf_path',$path)->first();
        if($invoice && $invoice->company_id == Auth::user()->company_id)
            return response()->file(storage_path('app/public/pdf/invoices/'.$path));
        else 
            abort(404);
    }

    public function resend(Request $request, Invoice $invoice)
    {
        $newInvoice = Invoice::create([
            'creator_id'    => $invoice->creator_id,
            'company_id'    => $invoice->company_id,
            'customer_id'   => null,
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
        return redirect()->route('invoice.index');
    }

    private function getServiceTotal(Invoice $invoice){   
        $total = 0;
        foreach($invoice->services as $service){
            $total += $service->price;
        }
        return $total;
    }
}

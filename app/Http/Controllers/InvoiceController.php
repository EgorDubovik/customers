<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use App\Models\InvoiceServices;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class InvoiceController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $invoices = Invoice::where('company_id',Auth::user()->company_id)->get();
        return view("invoice.index",['invoices' => $invoices]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view("invoice.create");
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
            'customer_id'   => null,
            'customer_name' => $request->customer_name,
            'address' => $request->customer_address,
            'email' => $request->email,
            'status' => 0,
            'pdf_path' => "path",
        ]);

        foreach($request->input('service-prices') as $key => $value){
            InvoiceServices::create([
                'invoice_id' => $invoice->id,
                'title' => $request->input('service-title')[$key],
                'description' => $request->input('service-description')[$key],
                'price' => $request->input('service-prices')[$key],
            ]);
        }

        return back();
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Invoice  $invoice
     * @return \Illuminate\Http\Response
     */
    public function show(Invoice $invoice)
    {
        //
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
}

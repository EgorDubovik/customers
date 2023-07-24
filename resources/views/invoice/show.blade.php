@extends('layout.main')

@section('content')

    <div class="main-container container-fluid">
        <!-- PAGE-HEADER -->
        <div class="page-header">
            <h1 class="page-title">Create new invoice</h1>
        </div>
        <!-- PAGE-HEADER END -->
        <!-- CONTENT -->
        <div class="row">

            <div class="col-md-6 m-auto">
                <div class="card">
                    <div class="card-header">
                        <spam class="text-muted">Access by link</spam>
                        <input type="text" class="form-control" value="{{ route('invoice.view.PDF',['key' => ($invoice->key) ? $invoice->key : "null"]) }}" readonly>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-lg-6">
                                <img src="{{ URL::asset('assets/images/brand/LogoForT-Shutsh.png')}}" style="width: 170px;" class="header-brand-img logo-3" alt="Sash logo">
                                <p style="margin-top: 20px"><b>{{ Auth::user()->company->name }}</b></p>
                                <div>
                                    <address class="pt-3">
                                        {{ Auth::user()->company->address->full }}<br>
                                        {{ Auth::user()->company->email }}
                                    </address>
                                </div>
                            </div>
                            <div class="col-lg-6 text-end border-bottom border-lg-0">
                                <h3>#INV-{{ $invoice->id }}</h3>
                                <h5>Date Issued: {{ \Carbon\Carbon::parse($invoice->created_at)->format('m-d-Y') }}</h5>
                            </div>
                        </div>
                        <div class="row pt-5">
                            <div class="col-lg-6">
                                <p class="h3">Invoice To:</p>
                                <p class="fs-18 fw-semibold mb-0"><span id="invoice-customer-name">{{ $invoice->customer_name }}</span></p>
                                <address>
                                        <span id="invoice-address">{!!  $invoice->address  !!}</span><br>
                                        <span id="invoice-email">{{ $invoice->email }}</span>
                                    </address>
                            </div>
                            <div class="col-lg-6 text-end">
                                <p class="h4 fw-semibold">Payment Details:</p>
                                <p class="mb-1">Total Due: $<span id='total-small-invoice'>{{ $total }}</span></p>
                                <p class="mb-1">Type of payment: Null</p>
                            </div>
                        </div>
                        <div class="table-responsive push">
                            <table class="table table-bordered table-hover mb-0 text-nowrap">
                                <tbody>
                                    <tr id="tr-header-invoice-table">
                                        <th class="text-center"></th>
                                        <th>Item</th>
                                        <th class="text-end">Total</th>
                                    </tr>
                                 
                                    @foreach ($invoice->services as $key => $service)
                                        <tr>
                                            <td class="text-center">{{ ($key+1) }}</td>
                                            <td>
                                                <p class="font-w600 mb-1">{{ $service->title }}</p>
                                                <div class="text-muted">
                                                    <div class="text-muted">{!! $service->description !!}</div>
                                                </div>
                                            </td>
                                            <td class="text-end">${{ $service->price }}</td>
                                        </tr>    
                                    @endforeach
                                    
                                    
                                    <tr>
                                        <td colspan="2" class="fw-bold text-uppercase text-end">Total</td>
                                        <td class="fw-bold text-end h4"><span id="total-invoice">${{ $total }}</span></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="card-footer text-end">
                        <form method="post" action="{{ route('invoice.resend',['invoice' => $invoice]) }}">
                            @csrf
                            <div class="input-group">
                                <button type="submit" class="btn btn-secondary mb-1" ><i class="si si-paper-plane"></i> Send New Copy</button>
                                <input type="text" id="main-email"  class="form-control" value="{{ $invoice->email }}" name="email">
                            </div>
                        </form>
                    </div>
                </div>    
            </div>
        </div>
        
    </div>
    
@stop
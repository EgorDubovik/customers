@extends('layout.main')

@section('css')
    <link href="{{ URL::asset('assets/plugins/typehead/jquery.typeahead.css') }}" rel="stylesheet" />
@endsection

@section('content')

    <div class="main-container container-fluid">
        <!-- PAGE-HEADER -->
        <div class="page-header">
            <h1 class="page-title">Create new invoice</h1>
        </div>
        <!-- PAGE-HEADER END -->
        <!-- CONTENT -->
        <form method="post" action="{{ route('invoice.store') }}">
            @csrf
            <input type="hidden" name="appointment_id" value="{{ $appointment->id }}">
            <div class="row">
                <div class="col-md-6">
                    <div class="card col-12">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-lg-6">
                                    @if (Auth::user()->company->logo)
                                        <img src="{{ URL::asset('storage/'.Auth::user()->company->logo) }}" style="width: 170px;" class="header-brand-img logo-3" alt="Sash logo">    
                                    @endif
                                    <p style="margin-top: 20px"><b>{{ Auth::user()->company->name }}</b></p>
                                    <div>
                                        <address>
                                            {{ Auth::user()->company->address->full }}<br>
                                        </address>
                                        {{ Auth::user()->company->phone }}<br>
                                        {{ Auth::user()->company->email }}
                                    </div>
                                </div>
                                <div class="col-lg-6 text-end border-bottom border-lg-0">
                                    <h3>#INV-000</h3>
                                    <h5>Date Issued: {{ date('M-d-Y') }}</h5>
                                </div>
                            </div>
                            <div class="row pt-5">
                                <div class="col-lg-6">
                                    <p class="h3">Invoice To:</p>
                                    <p class="fs-18 fw-semibold mb-0">{{ $appointment->customer->name }}</p>
                                    <address>
                                        <span id="invoice-address">{{ $appointment->address->line1 }},
                                            {{ $appointment->address->line2 }}<br>
                                            {{ $appointment->address->city }} {{ $appointment->address->state }}
                                            {{ $appointment->address->zip }}</span><br>
                                        <span id="invoice-email">{{ $appointment->customer->email }}</span>
                                    </address>
                                </div>
                                <div class="col-lg-6 text-end">
                                    <p class="h4 fw-semibold">Payment Details:</p>
                                    <p class="mb-1">Total Due: $<span id='total-small-invoice'>00,00</span></p>
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
                                        @foreach ($appointment->services as $service)
                                            <tr class="table-invoice-line">
                                                <td class="text-center">1</td>
                                                <td>
                                                    <p class="font-w600 mb-1">{{ $service->title }}</p>
                                                    <div class="text-muted">
                                                        <div class="text-muted">{{ $service->description }}</div>
                                                    </div>
                                                </td>
                                                <td class="text-end">${{ $service->price }}</td>
                                            </tr>
                                        @endforeach
                                        <tr>
                                            <td colspan="2" class="fw-bold text-uppercase text-end">Total</td>
                                            <td class="fw-bold text-end h4"><span id="total-invoice">$
                                                    {!! $total !!}</span></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="card-footer">
                            <div class="input-group">
                                <button type="submit" class="btn btn-secondary mb-1"><i class="si si-paper-plane"></i>
                                    Send Invoice</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>

@stop


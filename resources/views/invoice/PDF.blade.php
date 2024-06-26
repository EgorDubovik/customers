<style>
.row {
    display: -ms-flexbox;
    display: flex;
    -ms-flex-wrap: wrap;
    flex-wrap: wrap;
    margin-right: -0.75rem;
    margin-left: -0.75rem;
    width: 100%;
}
.col-6{
    width: 50%;
    float: left;
}
h3, .h3 {
    font-size: 1.5rem;
}
h1, h2, h3, h4, h5, h6, .h1, .h2, .h3, .h4, .h5, .h6 {
    margin-bottom: 0.66em;
    font-family: inherit;
    font-weight: 400;
    line-height: 1.1;
    color: inherit;
}

.text-end {
    text-align: right !important;
}
.pt-3, .py-3 {
    padding-top: 0.75rem !important;
}
address {
    margin-bottom: 1rem;
    font-style: normal;
    line-height: inherit;
}
.card-body{
    
    width: 730px;
}

.mb-0, .my-0 {
    margin-bottom: 0 !important;
}
.fw-semibold {
    font-weight: 500 !important;
}
.fs-18 {
    font-size: 18px !important;
}
.invoice-customer-name{
    font-weight: bold;
}
.table{
    width: 100%;
}
.text-nowrap {
    white-space: nowrap !important;
}

.table th, .text-wrap table th {
    color: #2e3138;
    text-transform: uppercase;
    font-size: 0.875rem;
    font-weight: 400;
}
.table-hover tbody tr:hover, .table-hover tbody th {
    background-color: #f6f6fb;
}
table{
    border-collapse: collapse;
    margin-top: 20px;
}
th, td{
    padding: 5px;
    border:1px solid rgb(206, 206, 206);
    border-collapse: collapse;
}
p{
    margin: 0px;
    padding: 0px;
}
.pt-5{
    padding-top: 20px;
}
</style>

<div class="card-body">
    <div class="row">
        <div class="col-6">
            @if (Auth::user()->company->logo)
                                    <img src="{{ env('AWS_FILE_ACCESS_URL').Auth::user()->company->logo }}" style="width: 170px;" class="header-brand-img logo-3" alt="Sash logo">    
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
        <div class="col-6 text-end border-bottom border-lg-0">
            <h3>#INV-{{ $invoice->id }}</h3>
            <h5>Date Issued: {{ \Carbon\Carbon::parse($invoice->created_at)->format('m-d-Y') }}</h5>
        </div>
    </div>
    <div style="clear: both;margin-bottom:50px"></div>
    <div class="row pt-5">
        <div class="col-6">
            <p class="h3">Invoice To:</p>
            <p class="fs-18 fw-semibold mb-0"><span id="invoice-customer-name"><b>{{ $invoice->customer->name }}</b></span></p>
            <address>
                <span id="invoice-address">{!!  $invoice->address  !!}</span><br>
            </address>
            <p class="mb-0"><span id="invoice-phone">{{ $invoice->customer->phone }}</span></p>
            <p class="mb-0"><span id="invoice-email">{{ $invoice->email }}</span></p>
        </div>
        <div class="col-6 text-end">
            <p class="h4 fw-semibold">Payment Details:</p>
            <p class="mb-1">Total Due: $<span id='total-small-invoice'>{{ $due }}</span></p>
            {{-- <p class="mb-1">Type of payment: Null</p> --}}
        </div>
    </div>
    <div style="clear: both"></div>
    <div class="table-responsive push row">
        
        @include('invoice.layout.services-table', ['services' => $invoice->appointment->services])
    </div>
    <p style="text-align: center;margin-top:50px;">Payments history:</p>
    <table style="width: 50%;border-spacing: 0px;border-collapse: collapse; border-style: outset;" align="right" border=0 >
        @foreach ($invoice->appointment->payments as $payment)
        <tr>
            <td>{{ Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $payment->created_at)->format('M d Y') }}</td>
            <td>{{ Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $payment->created_at)->format('g:i A') }}</td>
            <td>{{ App\Models\Payment::getPaymentTypeText($payment->payment_type) }}</td>
            <td>${{ number_format($payment->amount,2) }}</td>
        </tr>
        @endforeach
    </table>
</div>
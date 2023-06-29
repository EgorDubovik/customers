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
            <img src="{{ URL::asset('assets/images/brand/LogoForT-Shutsh.png')}}" style="width: 170px;" class="header-brand-img logo-3" alt="Sash logo">
            <p style="margin-top: 20px"><b>EDService LLC</b></p>
            <div>
                <address class="pt-3">
                    2901 Ridgeview dr, APT 426, Plano TX 75025<br>
                    edservicetx@gmail.com
                </address>
            </div>
        </div>
        <div class="col-6 text-end border-bottom border-lg-0">
            <h3>#INV-{{ $invoice->id }}</h3>
            <h5>Date Issued: {{ \Carbon\Carbon::parse($invoice->created_at)->format('m-d-Y') }}</h5>
        </div>
    </div>
    <div style="clear: both;"></div>
    <div class="row pt-5">
        <div class="col-6">
            <p class="h3">Invoice To:</p>
            <p class="fs-18 fw-semibold mb-0"><span id="invoice-customer-name"><b>{{ $invoice->customer_name }}</b></span></p>
            <address>
                    <span id="invoice-address">{!!  $invoice->address  !!}</span><br>
                    <span id="invoice-email">{{ $invoice->email }}</span>
                </address>
        </div>
        <div class="col-6 text-end">
            <p class="h4 fw-semibold">Payment Details:</p>
            <p class="mb-1">Total Due: $<span id='total-small-invoice'>{{ $total }}</span></p>
            <p class="mb-1">Type of payment: Null</p>
        </div>
    </div>
    <div style="clear: both"></div>
    <div class="table-responsive push row">
        <table class="table table-bordered table-hover mb-0 text-nowrap">
            <tbody>
                <tr id="tr-header-invoice-table">
                    <th class="text-center" style="width: 50px;"></th>
                    <th>Item</th>
                    <th class="text-end" style="width: 80px;">Total</th>
                </tr>
             
                @foreach ($invoice->services as $key => $service)
                    <tr>
                        <td class="text-center">{{ ($key+1) }}</td>
                        <td>
                            <p class="font-w600 mb-1">{{ $service->title }}</p>
                            <div class="text-muted">
                                <div class="text-muted"><p style="color: #808080">{!! $service->description !!}</p></div>
                            </div>
                        </td>
                        <td class="text-end">${{ $service->price }}</td>
                    </tr>    
                @endforeach
                
                
                <tr>
                    <td colspan="2" class="fw-bold text-uppercase text-end"><b>Total</b></td>
                    <td class="fw-bold text-end h4"><span id="total-invoice"><b>${{ $total }}</b></span></td>
                </tr>
            </tbody>
        </table>
    </div>
</div>

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
            <span class="company_phone">{{ Auth::user()->company->phone }}</span><br>
            <span class="email">{{ Auth::user()->company->email }}</span>
      </div>
   </div>
   <div class="col-6 text-end border-bottom border-0">
      <h3 class="text-end">#INV-{{ $invoice->id }}</h3>
      <h5 class="text-end">Date Issued: {{ \Carbon\Carbon::parse($invoice->created_at)->format('m-d-Y') }}</h5>
   </div>
</div>
<div class="row pt-5">
   <div class="col-6">
      <p class="h3">Invoice To:</p>
      <p class="fs-18 fw-semibold mb-0"><span id="invoice-customer-name">{{ $invoice->customer_name }}</span></p>
      <address>
         <span id="invoice-address">{!!  $invoice->address  !!}</span><br>
         <span id="invoice-email">{{ $invoice->email }}</span>
      </address>
   </div>
   <div class="col-6 text-end">
      <p class="h4 fw-semibold text-end">Payment Details:</p>
      <p class="mb-1 text-end">Total Due: $<span id='total-small-invoice'>{{ $due }}</span></p>
      {{-- <p class="mb-1">Type of payment: Null</p> --}}
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
            @foreach ($invoice->appointment->services as $key => $service)
               <tr>
                  <td class="text-center">{{ ($key+1) }}</td>
                  <td>
                     <p class="font-w600 mb-1">{{ $service->title }}</p>
                     <div class="text-muted">
                        <div class="text-muted">{!! nl2br($service->description) !!}</div>
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
<p style="text-align: center;margin-top:50px;">Payments history:</p>
<table style="width: 50%;" align="right" border=0>
   @foreach ($invoice->appointment->payments as $payment)
   <tr>
      <td>{{ Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $payment->created_at)->format('M d Y') }}</td>
      <td>{{ Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $payment->created_at)->format('g:i A') }}</td>
      <td>{{ App\Models\Payment::getPaymentTypeText($payment->payment_type) }}</td>
      <td>${{ number_format($payment->amount,2) }}</td>
   </tr>
   @endforeach
</table>
   
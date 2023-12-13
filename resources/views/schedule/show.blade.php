@extends('layout.main')

@section('css')
    <link href="{{ URL::asset('assets/plugins/autocomplete/jquery.autocompleter.css') }}" rel="stylesheet" />
@endsection

@section('content')

    <div class="main-container container-fluid px-0">
        <!-- CONTENT -->

        <div class="col-lg-8">
            <div class="row" style="padding-top: 20px;">
                <div class="col-md-6">
                    @include('layout/success-message', ['status' => 'success'])
                    <div class="cont-appointment-buttons">
                        <livewire:appointment.button-finish-appointment :appointment=$appointment />
                    </div>

                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Customer information</h3>
                        </div>
                        <div class="view_map_point" id="customer_map">

                        </div>
                        <div class="card-body">
                            
                            <ul class="list-group list-group-flush">
                                <li class="list-group-item">
                                    <a href="{{ route('customer.show', ['customer' => $appointment->customer]) }}" class="appointment-cutomer-name">{{ $appointment->customer->name }}</a>
                                    <a href="{{ route('customer.edit', ['customer' => $appointment->customer]) }}?redirect={{ route('appointment.show',$appointment) }}">
                                        <i class="fe fe-edit text-success pull-right"></i>
                                    </a>
                                </li>
                                <li class="list-group-item">{{ $appointment->address->full }}</li>
                                <li class="list-group-item">{{ $appointment->customer->phone }}</li>
                                <li class="list-group-item">{{ $appointment->customer->email }} 
                                    <a href="{{ route('invoice.create', ['appointment' => $appointment]) }}"><i
                                       class="fe fe-send pull-right text-secondary" style="cursor: pointer"></i></a>
                                </li>
                                
                            </ul>
                            
                            {{-- <p>
                                <span style="font-size:20px;"><a
                                        href="{{ route('customer.show', ['customer' => $appointment->customer]) }}">{{ $appointment->customer->name }}</a></span>
                                <a href="{{ route('customer.edit', ['customer' => $appointment->customer]) }}?redirect={{ route('appointment.show',$appointment) }}">
                                    <i class="fe fe-edit text-success pull-right"></i>
                                </a>
                            </p>
                            <p> --}}
                                
                                {{-- @if($appointment->address_id == 0)
                                    <span class="fs-14 fw-bold">{{ $appointment->customer->address->last()->full }}</span>
                                @else
                                    <span class="fs-14 fw-bold">{{ $appointment->address->full }}</span>
                                @endif --}}

                                {{-- <span class="fs-14 fw-bold">{{ $appointment->address->full }}</span>
                                <i class="fe fe-copy pull-right text-secondary" style="cursor: pointer"></i>
                                <a href="#"> <i class="fe fe-map-pin pull-right" style="margin-right: 10px;"></i></a>
                            </p>
                            <p>
                                <span class="fs-14 text-info fw-bold">{{ $appointment->customer->phone }}</span>
                                <i class="fe fe-copy pull-right text-secondary"
                                    onclick="copy_to({{ $appointment->customer->phone }})" style="cursor: pointer"></i>
                                <a href="#"> <i class="fe fe-phone-call pull-right"
                                        style="margin-right: 10px;"></i></a>
                            </p>
                            <p>
                                @if($appointment->customer->email)
                                <span class="fs-14 text-black">{{ $appointment->customer->email }}</span>
                                <a
                                    href="{{ route('invoice.create', ['appointment' => $appointment]) }}"><i
                                        class="fe fe-send pull-right text-secondary" style="cursor: pointer"></i></a>
                                @endif
                            </p> --}}

                        </div>

                    </div>
                </div>
                <div class="col-md-6">
                    
                    <livewire:appointment.services :appointment=$appointment isViewTaxable='true'/>
                    
                    <livewire:appointment.tech-block :appointment=$appointment />

                    <livewire:appointment.notes :appointment=$appointment />
                    
                    <div class="remove-appointment-block">
                        <form method="post" onsubmit="return confirm('Are you sure?')" action="{{ route('appointment.remove',['appointment'=>$appointment]) }}">
                            @csrf
                            @method('delete')
                            <button type="submit" class="btn btn-link" style="text-decoration: none">remove appointment</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{--Ppayemnt model--}}
    <div class="modal fade" id="payment_model" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content modal-content-demo">
                <div class="modal-header">
                    <h6 class="modal-title">Payment
                        <span style="color: #5f5f5f; margin-left:20px;">(Total: $<span id="paymentTotal">{{ $appointment->services->sum('price') }}</span>)</span>
                    </h6>
                    <button aria-label="Close" class="btn-close" data-bs-dismiss="modal"><span aria-hidden="true">×</span></button>
                </div>
                <form method="post" action="{{ route('appointment.pay', ['appointment' => $appointment]) }}">
                    @csrf
                    <div class="row p-2">
                        <div class="col-10">Remainig payment:</div> 
                        <div class="col-2"> <b>$<span id="remainingBalanceSpan">{{ $remainingBalance }}</span></b></div>
                        <input type='hidden' id="remainingBalance" value="{{ $remainingBalance }}" />
                        <input type="hidden" id="paymentsSum" value="{{ $appointment->payments->sum('amount') }}" />
                    </div>
                    <div class="modal-body">
                        <div class="amount-pay">
                            <span>$</span><input id="amountPayment" type="text" name="amount" value="{{ $remainingBalance }}">
                        </div>
                        <div class="btn-cont-payment">
                            @if ($remainingBalance > Auth::user()->settings->payment_deposit_amount)
                            <button 
                                type="button" 
                                onClick="setAmount(this)" 
                                data-amount="{{ Auth::user()->settings->payment_deposit_type==0 ? Auth::user()->settings->payment_deposit_amount : Auth::user()->settings->payment_deposit_amount_prc }}"
                                data-type="{{ Auth::user()->settings->payment_deposit_type  }}" 
                                class="btn btn-outline-primary" 
                                style="margin-right: 30px;">Deposit ( {!! Auth::user()->settings->payment_deposit_type==0 ? '$'.Auth::user()->settings->payment_deposit_amount : Auth::user()->settings->payment_deposit_amount_prc.'%' !!})
                            </button>
                            @endif
                            <button type="button" onClick="setAmount(this)" data-type=0 data-amount="{{ $remainingBalance }}" class="btn btn-outline-primary" id="buttonFull">Full</button>
                        </div>
                        <div class="type-of-payment">
                            Type of payment:
                            <div class="btn_cont_type">
                                <input type="hidden" name="payment_type" value="{{ \App\Models\Payment::CREDIT }}">
                                <div class="btn-group d-flex">
                                    <button type="button" onClick="setPaymentType(this)" data-type="{{ \App\Models\Payment::CREDIT }}" class="btn btn-outline-primary w-100 active">Credit</button>
                                    <button type="button" onClick="setPaymentType(this)" data-type="{{ \App\Models\Payment::TRANSFER }}" class="btn btn-outline-primary w-100">Transfer</button>
                                    <button type="button" onClick="setPaymentType(this)" data-type="{{ \App\Models\Payment::CASH }}" class="btn btn-outline-primary w-100">Cash</button>
                                    <button type="button" onClick="setPaymentType(this)" data-type="{{ \App\Models\Payment::CHECK }}" class="btn btn-outline-primary w-100">Check</button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-success" type="submit">Pay</button> <button class="btn btn-light" data-bs-dismiss="modal" onclick="return false;">Close</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

@stop

@section('scripts')

    <script async src="https://maps.googleapis.com/maps/api/js?key={{ env('GOOGLE_MAP') }}&callback=initMap"></script>
    
    <script>
        let noPoi = [{
            featureType: "poi",
            stylers: [{
                visibility: "off"
            }]
        }];
        let map;
        let geocoder;
        let address = "{{ $appointment->address->full }}";

        function initMap() {
            geocoder = new google.maps.Geocoder();
            let point = {
                lat: -34.397,
                lng: 150.644
            };
            map = new google.maps.Map(document.getElementById("customer_map"), {
                center: point,
                zoom: 13,
                disableDefaultUI: true,
            });
            codeAddress(address);
            map.setOptions({
                styles: noPoi
            });
        }

        function codeAddress(address) {
            geocoder.geocode({
                'address': address
            }, function(results, status) {
                var latLng = {
                    lat: results[0].geometry.location.lat(),
                    lng: results[0].geometry.location.lng()
                };
                if (status == 'OK') {
                    map.setCenter(results[0].geometry.location);
                    var marker = new google.maps.Marker({
                        position: latLng,
                        map: map
                    });
                } else {
                    alert('Geocode was not successful for the following reason: ' + status);
                }
            });
        }

        window.initMap = initMap;
    </script>

    <script>
        let typeOfServiceAction = 'add';
        function copy_to(text) {
            navigator.clipboard.writeText(text);
        }

        
        function setAmount(b){
            var type = $(b).attr('data-type');
            var remainingBalance = $('#remainingBalance').val();
            if(type == 0){
                var newAmount = $(b).attr('data-amount');
            } else if(type == 1){
                var procent = $(b).attr('data-amount');
                var newAmount = (remainingBalance * procent) / 100;
            }
            
            $('#amountPayment').val(newAmount);
            return false;
        }

        function setPaymentType(b){
            $(b).parent().find('button').removeClass('active');
            $(b).addClass('active');
            $(b).parent().parent().find('input').val($(b).attr('data-type'));
        }

        function openPayModal(){
            
            let remainingBalance = getRemainingBalance();
            
            $('#remainingBalance').val(remainingBalance.rem);
            $('#amountPayment').val(remainingBalance.rem);
            $('#remainingBalanceSpan').html(remainingBalance.rem);
            $('#buttonFull').attr('data-amount',remainingBalance.rem);
            $('#paymentTotal').html(remainingBalance.total);
            $('#payment_model').modal('show');
        }
        
        function viewTotal(){
            let remainingBalance = getRemainingBalance();
            if(remainingBalance.rem <= 0){
                $('#total_on_span').html('Paid full').addClass('tag-outline-success').removeClass('tag-outline-danger')
            } else {
                $('#total_on_span').html('Total due: $'+remainingBalance.rem).removeClass('tag-outline-success').addClass('tag-outline-danger')
            }
        }

        function getRemainingBalance(){
            var totalPrice = 0;
            $('#services-list li').each(function(){
                totalPrice += parseFloat($(this).attr('data-price'));
            });
            totalPrice = totalPrice.toFixed(2);
            var paymentsSum = $('#paymentsSum').val();
            var remainingBalance = (totalPrice-paymentsSum < 0) ? 0 : totalPrice-paymentsSum;
            
            remainingBalance = remainingBalance.toFixed(2);
            return {'rem':remainingBalance,'total':totalPrice};
        }
    </script>
    
    <script>
        window.addEventListener('close-modal', event => {
            $('#add_new_tech_model').modal('hide');
        })
    </script>
    
    @include('service.typehead-script')
    
@endsection

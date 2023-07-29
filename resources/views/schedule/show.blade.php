@extends('layout.main')

@section('css')
    <link href="{{ URL::asset('assets/plugins/typehead/jquery.typeahead.css') }}" rel="stylesheet" />
@endsection

@section('content')

    <div class="main-container container-fluid px-0">
        <!-- CONTENT -->

        <div class="col-lg-8">
            <div class="row" style="padding-top: 20px;">
                <div class="col-md-6">
                    @include('layout/success-message', ['status' => 'success'])
                    <div class="cont-appointment-buttons">
                        <div class="btn-group d-flex" role="group">
                            @if ($appointment->status == App\Models\Appointment::ACTIVE)
                                <a href="{{ route('appointment.change_status', ['appointment' => $appointment]) }}"
                                    class="btn btn-success col-5">
                                    <i class="fa fa-check"></i> Finish appointment
                                </a>
                            @else
                                <a href="{{ route('appointment.change_status', ['appointment' => $appointment]) }}"
                                    class="btn  btn-default col-5">
                                    <i class="fa fa-angle-double-left"></i> Back to Active
                                </a>
                            @endif

                            <a href="#" class="btn btn-success col-5">
                                <i class="fe fe-copy"></i> Create copy
                            </a>
                            <a data-bs-toggle="modal" href="#payment_model" class="btn btn-secondary col-2">
                                <i class="fa fa-credit-card"></i> Pay
                            </a>
                        </div>
                    </div>

                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Customer information</h3>
                        </div>
                        <div class="view_map_point" id="customer_map">

                        </div>
                        <div class="card-body">
                            <p>
                                <span style="font-size:20px;"><a
                                        href="{{ route('customer.show', ['customer' => $appointment->customer]) }}">{{ $appointment->customer->name }}</a></span>
                                <a href="{{ route('customer.edit', ['customer' => $appointment->customer]) }}">
                                    <i class="fe fe-edit text-success pull-right"></i>
                                </a>
                            </p>
                            <p>
                                @if($appointment->address_id == 0)
                                <span class="fs-14 fw-bold">{{ $appointment->customer->address->last()->full }}</span>
                                @else
                                <span class="fs-14 fw-bold">{{ $appointment->address->full }}</span>
                                @endif
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
                                <span class="fs-14 text-black">{{ $appointment->customer->email }}</span>
                                <a
                                    href="{{ route('invoice.create', ['appointment' => $appointment]) }}"><i
                                        class="fe fe-send pull-right text-secondary" style="cursor: pointer"></i></a>
                            </p>

                        </div>

                    </div>
                </div>
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title"><i class="fe fe-calendar"></i> Time</h3>
                            <div class="card-options">
                                <a href="{{ route('appointment.edit', ['appointment' => $appointment]) }}">
                                    <i class="fe fe-edit text-success"></i>
                                </a>
                            </div>
                        </div>
                        <div class="card-body">

                            <ul class="task-list">
                                <li class="d-sm-flex">
                                    <div>
                                        <i class="task-icon bg-primary"></i>
                                        <h6 class="fw-semibold fs-16">
                                            {{ Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $appointment->start)->format('H:i') }}<span
                                                class="text-muted fs-14 mx-2 fw-normal">
                                                {{ Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $appointment->start)->format('m-d Y') }}</span>
                                        </h6>
                                        {{-- <p class="text-muted fs-12"></p> --}}
                                    </div>
                                </li>
                                <li class="d-sm-flex">
                                    <div>
                                        <i class="task-icon bg-primary"></i>
                                        <h6 class="fw-semibold fs-16">
                                            {{ Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $appointment->end)->format('H:i') }}<span
                                                class="text-muted fs-14 mx-2 fw-normal">
                                                {{ Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $appointment->end)->format('m-d Y') }}</span>
                                        </h6>
                                        {{-- <p class="text-muted fs-12"></p> --}}
                                    </div>
                                </li>
                            </ul>

                            {{-- <p>Start time: <b>{{ Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $appointment->start)->format('H:i') }}</b> <span class="text-muted">{{ Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $appointment->start)->format('d-m Y') }}</span></p>
                        <p>End time: <b>{{ Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $appointment->end)->format('H:i') }}</b> <span class="text-muted">{{ Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $appointment->start)->format('d-m Y') }}</span></p> --}}
                        </div>
                        {{-- <div class="card-footer">
                        <p class="text-end"><a href="#">View all History ({{ count($appointments) }})</a></p>
                    </div> --}}
                    </div>

                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title"><i class="fe fe-list"></i> Services  
                                @if ($remainingBalance <= 0)
                                    <span class="tag tag-outline-success" style="margin-left: 30px;">Paid full</span>    
                                @endif
                            </h3>
                                
                            <div class="card-options">
                                <a href="#" onclick="$('#add_new_service_model').modal('show');return false;">
                                    <i class="fe fe-plus text-success"></i>
                                </a>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="line-services-added row">
                                @foreach ($appointment->services as $service)
                                    <div class="col-sm-12 col-md-6 p-1">
                                        <div class="cont-service-block">
                                            <div class="row mb-2">
                                                <div class="col-9"><b>{{ $service->title }}</b></div>
                                                <div class="col-3"><b>${{ $service->price }}</b></div>
                                            </div>
                                            <div class="hr"></div>
                                            <div class="row mt-2">
                                                <div class="col-9 iems-descrition">{{ $service->description }}</div>
                                                <div class="col-3">
                                                    <p class="text-end">
                                                        <a href="{{ route('appointment.service.remove', ['appointmentService' => $service]) }}"
                                                            onclick="if(confirm('Are you sure?')) return true; else return false; "
                                                            class=" text-danger"><i class="fa fa-trash"></i></a>
                                                    </p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                        <div class="card-footer">Total: <b
                                style="margin-left: 30px;">${{ $appointment->services->sum('price') }}</b></div>
                    </div>

                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title"><i class="fe fe-user"></i> Technical</h3>
                            <div class="card-options">
                                <a href="#" onclick="$('#add_new_tech_model').modal('show');return false;">
                                    <i class="fe fe-plus text-success"></i>
                                </a>
                            </div>
                        </div>
                        <div class="card-body">
                            @foreach ($appointment->techs as $tech)
                                <div class="media m-0 mt-0">
                                    <div class="avatar_cirle" style="background: {{ $tech->color }}"></div>
                                    <div class="media-body">
                                        <div class="row">
                                            <div class="col-10">
                                                <a href="#"
                                                    class="text-default fw-semibold">{{ $tech->name }}</a>
                                                <p class="text-muted ">
                                                    {{ $tech->phone }}
                                                </p>
                                            </div>
                                            <div class="col-2">
                                                <a href="{{ route('appointment.remove.tech', ['appointment' => $appointment, 'user' => $tech]) }}"
                                                    style="font-size:18px;color:#ff7979;"><i class="fa fa-trash"></i></a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title"><i class="fe fe-note"></i> Notes</h3>
                        </div>
                        <div class="card-body">
                            <form method="post"
                                action="{{ route('appointment.note.store', ['appointment' => $appointment]) }}">
                                @csrf
                                <div class="form-group">
                                    <div class="input-group">
                                        <textarea type="text" id="add-new-note" class="form-control" placeholder="Add new note to customer"
                                            name="text"></textarea>
                                        <button class="btn btn-secondary" type="send">
                                            <i class="fa fa-save"></i>
                                        </button>
                                    </div>
                                </div>
                            </form>
                            @foreach ($appointment->notes as $note)
                                <div class="media m-0 mt-2 border-bottom">
                                    <img class="avatar brround avatar-md me-3" alt="avatra-img"
                                        src="{{ URL::asset('/assets/images/users/18.jpg') }}">
                                    <div class="media-body">
                                        <a href="javascript:void(0)"
                                            class="text-default fw-semibold">{{ $note->creator->name }}</a>
                                        <p class="text-muted ">{!! $note->text !!}</p>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Add new service model --}}
    @include('layout.modals.add-service', ['place' => 'show'])
    @include('layout.modals.add-tech')

    {{--Ppayemnt model--}}
    <div class="modal fade" id="payment_model" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content modal-content-demo">
                <div class="modal-header">
                    <h6 class="modal-title">Payment <span style="color: #5f5f5f; margin-left:20px;">(Total: ${{ $appointment->services->sum('price') }})</span></h6><button aria-label="Close" class="btn-close" data-bs-dismiss="modal"><span aria-hidden="true">×</span></button>
                </div>
                <form method="post" action="{{ route('appointment.pay', ['appointment' => $appointment]) }}">
                    @csrf
                    <div class="row p-2">
                        <div class="col-10">Remainig payment:</div> 
                        <div class="col-2"> <b>${{ $remainingBalance }}</b></div>
                        <input type='hidden' id="remainingBalance" value="{{ $remainingBalance }}">
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
                            <button type="button" onClick="setAmount(this)" data-type=0 data-amount="{{ $remainingBalance }}" class="btn btn-outline-primary" >Full</button>
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
        let address = "{{ $appointment->customer->address->last()->full }}";

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
        function copy_to(text) {
            navigator.clipboard.writeText(text);
        }

        function confirmRemove() {
            if (confirm('Are you sure?'))
                return true;
            return false;
        }

        function removeServiceItem(d) {
            $(d).parent().parent().parent().parent().parent().remove();
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
    </script>
    @include('service.typehead-script')
@endsection

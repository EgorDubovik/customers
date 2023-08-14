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
                        <div class="btn-group d-flex" role="group" style="    background: #fff; padding: 10px; border-radius: 10px;">
                            @if ($appointment->status == App\Models\Appointment::ACTIVE)
                                <a href="{{ route('appointment.change_status', ['appointment' => $appointment]) }}"
                                    class="btn btn-outline-success col-5">
                                    <i class="fa fa-check"></i> Finish appointment
                                </a>
                            @else
                                <a href="{{ route('appointment.change_status', ['appointment' => $appointment]) }}"
                                    class="btn  btn-default col-5">
                                    <i class="fa fa-angle-double-left"></i> Back to Active
                                </a>
                            @endif

                            <a href="#" class="btn btn-outline-success col-5">
                                <i class="fe fe-copy"></i> Create copy
                            </a>
                            <button onclick="openPayModal();" class="btn btn-outline-secondary col-2">
                                <i class="fa fa-credit-card"></i> Pay
                            </button>
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
                            <h3 class="card-title"><i class="fe fe-list"></i> Infromation  
                                
                                {{-- @if ($remainingBalance <= 0)
                                    <span class="tag tag-outline-success" style="margin-left: 30px;">Paid full</span>    
                                @endif --}}
                            </h3>
                                
                            <div class="card-options">
                                
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="appointment-time-info d-flex">
                                <div>
                                    <b>Appointment time:</b> <span
                                    class="text-muted fs-14 mx-2 fw-normal">
                                    {{ Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $appointment->start)->format('M d Y') }}</span>
                                    {{ Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $appointment->start)->format('H:i') }} -
                                    {{ Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $appointment->end)->format('H:i') }}
                                </div>
                                <div class="ms-auto d-flex">
                                    <a href="{{ route('appointment.edit', ['appointment' => $appointment]) }}" class="text-muted me-2" data-bs-toggle="tooltip" data-bs-placement="top" title="" aria-label="Edit appointment time" data-bs-original-title="Edit appointment time"><span class="fe fe-edit"></span></a>
                                </div>
                            </div>
                            {{--  --}}
                            <div class="appointment-services">
                                <div class="appointment-services-title">
                                    Services:
                                </div>
                                <div class="line-services-added row" style="padding-left: 20px">
                                    <ul class="list-group list-group-flush services-list" id="services-list">
                                    @foreach ($appointment->services as $service)
                                        <li class="list-group-item d-flex" data-price="{{ $service->price }}">
                                            <div class="service-item-loading remove]">
                                                <div class="spinner-border text-secondary me-2" role="status">
                                                    <span class="visually-hidden">Loading...</span>
                                                </div>
                                            </div>
                                            <div>   
                                                <i class="task-icon bg-secondary"></i>
                                                <h6 class="fw-semibold">{{ $service->title }}<span class="text-muted fs-11 mx-2 fw-normal"> ${{ $service->price }}</span>
                                                </h6>
                                                <p class="text-muted fs-12">{{ $service->description }}</p>
                                            </div>
                                            <div class="ms-auto d-flex">
                                                <a href="javascript:void(0)" class="text-muted me-2" data-bs-toggle="tooltip" data-bs-placement="top" title="" aria-label="Edit" data-bs-original-title="Edit"><span class="fe fe-edit"></span></a>
                                                <a href="#" onclick="removeService(this,{{ $service->id }});return false" class="text-muted"><span class="fe fe-trash-2"></span></a>
                                            </div>
                                        </li>
                                        
                                    @endforeach
                                    </ul>
                                    <div class="text-center">
                                        <a href="#" onclick="$('#add_new_service_model').modal('show');return false;" class="text-secondary">+ add new service</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card-footer">
                            <div>Payment history:
                                @if ($remainingBalance <= 0)
                                    <span class="tag tag-outline-success" style="margin-left: 30px;">Paid full</span>    
                                @else
                                    <span class="tag tag-outline-danger" style="margin-left: 30px;">Total due: ${{ $remainingBalance }}</span>
                                @endif
                            </div>
                            <table style="width: 50%;" align="right" class="table-payment-history">
                                @foreach ($appointment->payments as $payment)
                                <tr>
                                    <td>{{ Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $payment->created_at)->format('M d Y') }}</td>
                                    <td>{{ Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $payment->created_at)->format('g:i A') }}</td>
                                    <td>{{ App\Models\Payment::getPaymentTypeText($payment->payment_type) }}</td>
                                    <td>${{ number_format($payment->amount,2) }}</td>
                                </tr>
                                @endforeach
                            </table>
                        </div>
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
                                                    class="text-muted"><i class="fe fe-trash-2"></i></a>
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
                    <div class="card">
                        <form method="post" onsubmit="return confirm('Are you sure?')" action="{{ route('appointment.remove',['appointment'=>$appointment]) }}">
                            @csrf
                            @method('delete')
                            <button type="submit" class="btn btn-danger btn-block">remove appointment</button>
                        </form>
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
        function copy_to(text) {
            navigator.clipboard.writeText(text);
        }

        function addNewService(d){
            var title = $('#title').val();
            var price = $('#price').val();
            var description = $('#description').val();
            
            if(title == ""){
                alert("Plese add title");
                return false;
            }
            $.ajax({
                method:'post',
                url:"{{ route('appointment.add.serivce', ['appointment'=>$appointment]) }}",
                data:{
                    _token : "{{ csrf_token() }}",
                    title : title,
                    price : price,
                    description : description,
                },
            }).done(function(data) {
                
                if(data.appointment)
                    addServiceHTML(data.appointment);
                else
                    alert('error');
            })
            .fail(function() {
                alert("error");
            });
        }

        function addServiceHTML(appointment){
            $("#services-list").append('<li class="list-group-item d-flex" data-price="'+appointment.price+'">'+
                                            '<div class="service-item-loading adding">'+
                                                '<div class="spinner-border text-secondary me-2" role="status">'+
                                                    '<span class="visually-hidden">Loading...</span>'+
                                                '</div>'+
                                            '</div>'+
                                            '<div>'+
                                                '<i class="task-icon bg-secondary"></i>'+
                                                '<h6 clas="fw-semibold">'+appointment.title+'<span class="text-muted fs-11 mx-2 fw-normal"> $'+appointment.price+'</span>'+
                                                '</h6>'+
                                                '<p class="text-muted fs-12">'+appointment.description+'</p>'+
                                            '</div>'+
                                            '<div class="ms-auto d-flex">'+
                                                '<a href="javascript:void(0)" class="text-muted me-2" data-bs-toggle="tooltip" data-bs-placement="top" title="" aria-label="Edit" data-bs-original-title="Edit"><span class="fe fe-edit"></span></a>'+
                                                '<a href="#" onclick="removeService(this,'+appointment.id+');return false;" class="text-muted"><span class="fe fe-trash-2"></span></a>'+
                                            '</div>'+
                                        '</li>');
            $('#title').val('');
            $('#price').val('');
            $('#description').val('');
            $('#add_new_service_model').modal('hide');
        }

        function removeService(d,id) {
            var parent = $(d).parent().parent();
            parent.find('.service-item-loading').addClass('active').addClass('remove');
            $.ajax({
                method:'post',
                url:"/appointment/remove-service/"+id,
                data:{
                    _token : "{{ csrf_token() }}",
                },
                
            }).done(function(data) {
                parent.remove();
            })
            .fail(function() {
                alert("error");
            });
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
            var totalPrice = 0;
            $('#services-list li').each(function(){
                totalPrice += parseFloat($(this).attr('data-price'));
            });
            totalPrice = totalPrice.toFixed(2);
            var paymentsSum = $('#paymentsSum').val();
            var remainingBalance = (totalPrice-paymentsSum < 0) ? 0 : totalPrice-paymentsSum;
            
            remainingBalance = remainingBalance.toFixed(2);
            console.log(remainingBalance);
            $('#remainingBalance').val(remainingBalance);
            $('#amountPayment').val(remainingBalance);
            $('#remainingBalanceSpan').html(remainingBalance);
            $('#buttonFull').attr('data-amount',remainingBalance);
            $('#paymentTotal').html(totalPrice);
            $('#payment_model').modal('show');
        }

    </script>
    @include('service.typehead-script')
@endsection

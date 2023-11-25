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
                        <livewire:button-finish-appointment :appointment=$appointment />
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
                                @if($appointment->customer->email)
                                <span class="fs-14 text-black">{{ $appointment->customer->email }}</span>
                                <a
                                    href="{{ route('invoice.create', ['appointment' => $appointment]) }}"><i
                                        class="fe fe-send pull-right text-secondary" style="cursor: pointer"></i></a>
                                @endif
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
                                        <li class="list-group-item d-flex" 
                                            data-price="{{ $service->price }}" 
                                            data-title="{{ $service->title }}"
                                            data-description="{{ $service->description }}"
                                            data-id="{{ $service->id }}"
                                        >
                                            <div class="service-item-loading remove">
                                                <div class="spinner-border text-secondary me-2" role="status">
                                                    <span class="visually-hidden">Loading...</span>
                                                </div>
                                            </div>
                                            <div>   
                                                <i class="task-icon bg-secondary"></i>
                                                <h6 class="fw-semibold">{{ $service->title }}<span class="text-muted fs-11 mx-2 fw-normal"> ${{ $service->price }}</span>
                                                </h6>
                                                <p class="text-muted fs-12">{!! nl2br($service->description) !!}</p>
                                            </div>
                                            <div class="ms-auto d-flex">
                                                <a href="#" onclick="openServiceModal('edit',this); return false" class="text-muted me-2" data-bs-toggle="tooltip" data-bs-placement="top" title="" aria-label="Edit" data-bs-original-title="Edit"><span class="fe fe-edit"></span></a>
                                                <a href="#" onclick="removeService(this,{{ $service->id }});return false" class="text-muted"><span class="fe fe-trash-2"></span></a>
                                            </div>
                                        </li>
                                        
                                    @endforeach
                                    </ul>
                                    <div class="text-center">
                                        <a href="#" onclick="openServiceModal('add',this);return false;" class="text-secondary">+ add new service</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card-footer">
                            <div>Payment history:
                                @if ($remainingBalance <= 0)
                                    <span class="tag tag-outline-success" id="total_on_span" style="margin-left: 30px;">Paid full </span>    
                                @else
                                    <span class="tag tag-outline-danger" id="total_on_span" style="margin-left: 30px;">Total due: ${{ $remainingBalance }}</span>
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

                    <livewire:tech-block :appointment=$appointment />

                    <livewire:appointment.notes :appointment=$appointment />
                    
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
    @include('layout.modals.add-service')
    

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

        function openServiceModal(action,d){
            typeOfServiceAction = action;
            if(typeOfServiceAction=='edit'){
                let li = $(d).parent().parent();
                $('#title').val(li.attr('data-title'));
                $('#price').val(li.attr('data-price'));
                $('#description').val(li.attr('data-description'));
                $('#service-id').val(li.attr('data-id'));

            } else if(typeOfServiceAction == 'add'){
                $('#title').val('');
                $('#price').val('');
                $('#description').val('');
                $('#service-id').val('');
            }
            $('#add_new_service_model').modal('show');
        }

        function serviceModalAction(d){
            if(typeOfServiceAction == 'add')
                addNewService(d);

            if(typeOfServiceAction == 'edit')
                edditService(d);

            $('#add_new_service_model').modal('hide');
        }

        function edditService(d){
            var title = $('#title').val();
            var price = $('#price').val();
            var description = $('#description').val();
            var serviceId = $('#service-id').val();
            $.ajax({
                method:'post',
                url:"{{ route('appointment.service.update') }}",
                data:{
                    _token : "{{ csrf_token() }}",
                    title : title,
                    price : price,
                    description : description,
                    serviceId : serviceId,
                },
            }).done(function(data) {
                if(!data.service){
                    alert('Error, reload the page');
                    return;
                }
                changeServiceHTML(data.service);

            })
            .fail(function() {
                alert("error");
            });
        }

        function changeServiceHTML(service){
            let ul = $('#services-list');
            let lis = ul.find('li');
            if(lis.length == 0) return ;
            if(lis.length == 1){
                lis[0].remove();
                ul.append(returnServiceHtml(service));
            } else {
                let index = null;
                lis.each(function(){
                    if($(this).attr('data-id') == service.id){
                        index = $(this).index();
                    }
                });
                if(typeof index === "null")
                    return ;

                ul.find('li').eq(index).remove();
                if(index == 0){
                    ul.prepend(returnServiceHtml(service))
                } else {
                    $("#services-list > li:nth-child(" + (index) + ")").after(returnServiceHtml(service));
                }
            }
            viewTotal();
            
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
                url:"{{ route('appointment.serivce.store', ['appointment'=>$appointment]) }}",
                data:{
                    _token : "{{ csrf_token() }}",
                    title : title,
                    price : price,
                    description : description,
                },
            }).done(function(data) {
                if(!data.service){
                    alert('error');
                    return ;
                }
                addServiceHTML(data.service);    
                viewTotal();
            })
            .fail(function() {
                alert("error");
            });
        }

        function addServiceHTML(service){
            $("#services-list").append(returnServiceHtml(service));
            $('#title').val('');
            $('#price').val('');
            $('#description').val('');
        }

        function returnServiceHtml(service){
            return '<li class="list-group-item d-flex" data-price="'+service.price+'" data-title = "'+service.title+'" data-description = "'+service.description+'" data-id = "'+service.id+'">'+
                        '<div class="service-item-loading adding">'+
                            '<div class="spinner-border text-secondary me-2" role="status">'+
                                '<span class="visually-hidden">Loading...</span>'+
                            '</div>'+
                        '</div>'+
                        '<div>'+
                            '<i class="task-icon bg-secondary"></i>'+
                            '<h6 class="fw-semibold">'+service.title+'<span class="text-muted fs-11 mx-2 fw-normal"> $'+service.price+'</span>'+
                            '</h6>'+
                            '<p class="text-muted fs-12">'+service.description+'</p>'+
                        '</div>'+
                        '<div class="ms-auto d-flex">'+
                            '<a href="#" onclick="openServiceModal(\'edit\',this); return false" class="text-muted me-2" data-bs-toggle="tooltip" data-bs-placement="top" title="" aria-label="Edit" data-bs-original-title="Edit"><span class="fe fe-edit"></span></a>'+
                            '<a href="#" onclick="removeService(this,'+service.id+');return false;" class="text-muted"><span class="fe fe-trash-2"></span></a>'+
                        '</div>'+
                    '</li>';
        }

        function removeService(d,id) {
            var parent = $(d).parent().parent();
            parent.find('.service-item-loading').addClass('active').addClass('remove');
            $.ajax({
                method:'post',
                url:"/appointment/service/remove/"+id,
                data:{
                    _token : "{{ csrf_token() }}",
                },
                
            }).done(function(data) {
                parent.remove();
                viewTotal();
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

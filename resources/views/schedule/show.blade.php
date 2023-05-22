@extends('layout.main')

@section('content')

    <div class="main-container container-fluid px-0">
        <!-- CONTENT -->

        <div class="col-lg-8">
        <div class="row" style="padding-top: 20px;">
            <div class="col-md-6">
                @include('layout/success-message',['status' => 'success'])
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Customer information</h3>
                    </div>
                    <div class="view_map_point" id="customer_map">

                    </div>
                    <div class="card-body">
                    
                        
                            <p>
                                <span style="font-size:20px;">{{$appointment->customer->name}}</span>
                                <a href="{{route('customer.edit',['customer' => $appointment->customer])}}">
                                    <i class="fe fe-edit text-success pull-right"></i>
                                </a>
                            </p>
                            <p>
                                <span class="fs-14 fw-bold">{{$appointment->customer->address->full}}</span>
                                <i class="fe fe-copy pull-right text-secondary" style="cursor: pointer"></i>
                                <a href="#"> <i class="fe fe-map-pin pull-right" style="margin-right: 10px;"></i></a>
                            </p>
                            <p>
                                <span class="fs-14 text-info fw-bold">{{$appointment->customer->phone}}</span>
                                <i class="fe fe-copy pull-right text-secondary" onclick="copy_to({{$appointment->customer->phone}})" style="cursor: pointer"></i>
                                <a href="#"> <i class="fe fe-phone-call pull-right" style="margin-right: 10px;"></i></a>
                            </p>
                            <p>
                                <span class="fs-14 text-black">{{$appointment->customer->email}}</span>
                                <a href="{{ route('invoice.create',['customer_id' => $appointment->customer->id]) }}"><i class="fe fe-send pull-right text-secondary" style="cursor: pointer"></i></a>
                            </p>
                        
                    </div>

                </div>
            </div>
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title"><i class="fe fe-calendar"></i> Time</h3>
                        <div class="card-options">
                            <a href="{{ route('appointment.edit',['appointment' => $appointment]) }}">
                                <i class="fe fe-edit text-success"></i>
                            </a>
                        </div>
                    </div>
                    <div class="card-body">

                        <ul class="task-list">
                            <li class="d-sm-flex">
                                <div>
                                    <i class="task-icon bg-primary"></i>
                                    <h6 class="fw-semibold fs-16">{{ Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $appointment->start)->format('H:i') }}<span class="text-muted fs-14 mx-2 fw-normal"> {{ Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $appointment->start)->format('m-d Y') }}</span>
                                    </h6>
                                    {{-- <p class="text-muted fs-12"></p> --}}
                                </div>
                            </li>
                            <li class="d-sm-flex">
                                <div>
                                    <i class="task-icon bg-primary"></i>
                                    <h6 class="fw-semibold fs-16">{{ Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $appointment->end)->format('H:i') }}<span class="text-muted fs-14 mx-2 fw-normal"> {{ Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $appointment->end)->format('m-d Y') }}</span>
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
                        <h3 class="card-title"><i class="fe fe-list"></i> Services</h3>
                        <div class="card-options">
                            <a href="#" onclick="$('#add_new_service_model').modal('show');return false;">
                                <i class="fe fe-plus text-success"></i>
                            </a>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="line-services-added" class="row">
                            @foreach ($appointment->services as $service)
                            <div class="col-sm-12 col-md-6 mb-2">
                               
                                <div class="cont-service-block">
                                    <div class="row mb-2">
                                        <div class="col-9"><b>{{ $service->title }}</b></div>
                                        <div class="col-3"><b>${{ $service->price }}</b></div>
                                    </div>
                                    <div class="hr"></div>
                                    <div class="row mt-2">
                                        <div class="col-9 iems-descrition">{{ $service->description }}</div>
                                        
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>

                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title"><i class="fe fe-user"></i> Technical</h3>
                        <div class="card-options">
                            <a href="#" onclick="$('#add_new_service_model').modal('show');return false;">
                                <i class="fe fe-plus text-success"></i>
                            </a>
                        </div>
                    </div>
                    <div class="card-body">
                        @foreach ($appointment->techs as $tech)
                            <div class="media m-0 mt-0">
                                <img class="avatar brround avatar-md me-3" alt="avatra-img" src="../../assets/images/users/18.jpg">
                                <div class="media-body">
                                    <div class="row">
                                        <div class="col-10">
                                            <a href="#" class="text-default fw-semibold">{{ $tech->name }}</a>
                                            <p class="text-muted ">
                                                {{ $tech->phone }}
                                            </p>
                                        </div>
                                        <div class="col-2">
                                            <a href="{{ route('appointment.remove.tech', ['appointment'=>$appointment,'user'=>$tech]) }}" class="text-danger"  style="font-size:18px;"><i class="fa fa-trash"></i></a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
        </div>
    </div>

    {{--Add new service model--}}
    @include('layout.modals.add-service')
    
@stop

@section('scripts')

    <script async src="https://maps.googleapis.com/maps/api/js?key={{ env('GOOGLE_MAP') }}&callback=initMap"></script>
    <script>
        let noPoi = [
            {
                featureType: "poi",
                stylers: [
                    { visibility: "off" }
                ]   
            }
        ];
        let map;
        let geocoder;
        let address = "{{ $appointment->customer->address->full }}";
        function initMap() {
            geocoder = new google.maps.Geocoder();
            let point = { lat: -34.397, lng: 150.644 };
            map = new google.maps.Map(document.getElementById("customer_map"), {
                center: point,
                zoom: 13,
                disableDefaultUI: true,
            });
            codeAddress(address);
            map.setOptions({styles: noPoi});
        }
        function codeAddress(address) {
            geocoder.geocode({ 'address': address }, function (results, status) {
                var latLng = {lat: results[0].geometry.location.lat (), lng: results[0].geometry.location.lng ()};
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
        function copy_to(text){
            navigator.clipboard.writeText(text);
        }

        function confirmRemove(){
            if (confirm('Are you sure?'))
                return true;
            return false;
        }
        function removeServiceItem(d){
            $(d).parent().parent().parent().parent().parent().remove();
        }
    </script>
@endsection

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
                {{-- Scheduling                --}}
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Appointment time</h3>
                    </div>
                    <div class="card-body">
                        
                    </div>
                    
                    {{-- <div class="card-footer">
                        <p class="text-end"><a href="#">View all History ({{ count($appointments) }})</a></p>
                    </div> --}}
                </div>


                {{-- Images                --}}
                {{-- <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Images</h3>
                    </div>
                    @include('layout/error-message')
                    <div class="card-body">
                        <form method="post" action="{{route('image.store',['customer' => $customer])}}" enctype="multipart/form-data">
                            @csrf
                            <div class="input-group mb-3">
                                <input class="form-control form-control-sm" name="images[]" type="file" multiple>
                                <div class="input-group-append">
                                    <button class="btn btn-primary btn-sm" type="submit">Upload</button>
                                </div>
                            </div>
                        </form>
                        <div class="row">
                            @foreach($customer->images as $image)
                                <div class="col-4 col-sm-2 customer-img-bl">
                                    <a href="{{route('image.delete',['image'=>$image])}}" onclick="return confirm('Are you sure?');"><span class="close">&times;</span></a>
                                    <img src="{{url($image->id.'/'.$image->path)}}" />
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div> --}}

                
                {{-- @include('customer.notes') --}}
            </div>
        </div>
        </div>
    </div>

    
    
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
    </script>
@endsection

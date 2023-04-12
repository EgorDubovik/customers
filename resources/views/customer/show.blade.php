@extends('layout.main')

@section('content')

    <div class="main-container container-fluid">
        <!-- CONTENT -->

        <div class="col-md-8">
        <div class="row" style="padding-top: 20px;">
            <div class="col-md-6">
                @include('layout/success-message',['status' => 'success'])
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Customer information</h3>
                        <div class="card-options">
                            <a href="{{route('customer.edit',['customer' => $customer])}}">
                                <i class="fe fe-edit text-success"></i>
                            </a>
                        </div>
                    </div>
                    <div class="card-body">
                        {{-- <div class="view_map_point"><img src="{{ URL::asset('assets/images/map.png') }}" style="width:100%"></div> --}}
                        <div class="row">
                            <div class="view_map_point" id="customer_map">

                            </div>
                            <p>
                                <span class="text-muted">Customer name:</span>
                                <span style="margin-left: 15px;">{{$customer->name}}</span>
                            </p>
                            <p>
                                <span class="fs-14 fw-bold">{{$customer->address->full}}</span>
                                <i class="fe fe-copy pull-right text-secondary" style="cursor: pointer"></i>
                                <a href="#"> <i class="fe fe-map-pin pull-right" style="margin-right: 10px;"></i></a>
                            </p>
                            <p>
                                <span class="fs-14 text-info fw-bold">{{$customer->phone}}</span>
                                <i class="fe fe-copy pull-right text-secondary" onclick="copy_to({{$customer->phone}})" style="cursor: pointer"></i>
                                <a href="#"> <i class="fe fe-phone-call pull-right" style="margin-right: 10px;"></i></a>
                            </p>
                            <p>
                                <span class="fs-14 text-black">{{$customer->email}}</span>
                                <a href="{{ route('invoice.create',['customer_id' => $customer->id]) }}"><i class="fe fe-send pull-right text-secondary" style="cursor: pointer"></i></a>
                            </p>
                        </div>
                    </div>

                </div>
            </div>
            <div class="col-md-6">
                {{-- Scheduling                --}}
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Scheduling</h3>
                        <div class="card-options">
                            <a href="{{route('schedule.create',['customer' => $customer->id])}}">
                                <i class="fe fe-plus-circle text-success"></i>
                            </a>
                        </div>
                    </div>
                    <div class="card-body">
                        <p class="text-muted">Upcoming appointment</p>
                    </div>
                </div>


                {{-- Images                --}}
                <div class="card">
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
                </div>

                <div class="card">
                    <div class="card-header">Customer Tags <a class="fs-16 text-orange" style="margin-left: 20px;" data-bs-toggle="modal" href="#add_new_tag_model"><i class="fe fe-plus-circle"></i> </a></div>
                    <div class="card-body">
                        <div class="tags-row mb-3">
                            @foreach($customer->tags as $tag)
                                <span class="tag tag-rounded tag-icon tag-orange">{{$tag->title}} <a href="{{route('tag.untie',[$customer,$tag])}}" class="tag-addon tag-addon-cross tag-orange"><i class="fe fe-x text-white m-1"></i></a></span>
                            @endforeach
                        </div>
                        <div class="row">
                            <form method="post" action="{{route('tag.assign',['customer' => $customer])}}">
                                @csrf
                                <div class="input-group">
                                    <select class="form-control form-select" name="tag_id">
                                        @foreach(\Illuminate\Support\Facades\Auth::user()->company_tags as $tag)
                                            <option value="{{$tag->id}}">{{$tag->title}}</option>
                                        @endforeach
                                    </select>
                                    <div class="input-group-append">
                                        <button class="btn btn-outline-primary" type="submit" id="button-addon2">Assign</button>
                                    </div>
                                </div>

                            </form>
                        </div>
                    </div>
                </div>
                @include('customer.notes')
            </div>
        </div>
        </div>
    </div>

    {{--Add new tag model--}}
    <div class="modal fade" id="add_new_tag_model" aria-hidden="true">
        <div class="modal-dialog modal-sm text-center" role="document">
            <div class="modal-content modal-content-demo">
                <form method="post" action="{{route('tag.store')}}">
                    @csrf
                    <div class="modal-header">
                        <h6 class="modal-title">Add new tag</h6><button aria-label="Close" class="btn-close" data-bs-dismiss="modal"><span aria-hidden="true">×</span></button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" name="customer_id" value="{{$customer->id}}">
                        <input class="form-control mb-4" placeholder="Tag title" name="title" type="text">
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-success" type="submit">Add</button> <button class="btn btn-light" data-bs-dismiss="modal">Close</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@stop

@section('scripts')

    <script src="{{ URL::asset('assets/plugins/leaflet/leaflet.js')}}"></script>
    <script async src="https://maps.googleapis.com/maps/api/js?key={{ env('GOOGLE_MAP') }}&callback=initMap"></script>
    <script>
        // // Adding a Popup
        // var map = L.map('customer_map').setView([51.505, -0.09], 13);
        // L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png').addTo(map);
        // map.on('2901 Ridgeview Dr, Plano TX 75025', function (result) {
        //     L.marker([result.x, result.y]).addTo(map)
        // });

        // // L.marker([51.5, -0.09]).addTo(map)
        // //     .openPopup();

        let map;
        let geocoder;
        let address = "{{ $customer->address->full }}";
        function initMap() {
            geocoder = new google.maps.Geocoder();
            let point = { lat: -34.397, lng: 150.644 };
            map = new google.maps.Map(document.getElementById("customer_map"), {
                center: point,
                zoom: 13,
                disableDefaultUI: true,
            });
            codeAddress(address);
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

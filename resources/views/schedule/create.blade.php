@extends('layout.main')

@section('content')
    <link href="{{ URL::asset('assets/css/drum.css')}}" rel="stylesheet" />
    <div class="main-container container-fluid">
        <!-- PAGE-HEADER -->
        <div class="page-header">
            <h1 class="page-title">Create Appointment</h1>
        </div>
        <!-- PAGE-HEADER END -->
        <!-- CONTENT -->
        <div class="row">
            <div class="col-md-4 m-auto">
                <div >
                    <div class="card">
                        <form method="post" >
                            @csrf
                            <div class="card-body">
                                @if($errors->any())
                                    @include("layout/error-message")
                                @endif
                                <div class="row mb-2">
                                    <label  class="col-md-3 form-label">Customer</label>
                                    <div class="col-md-9">
                                        <div class="content-customer-scheduling">
                                            @if(isset($customer))
                                                <input type="hidden" value="{{$customer->id}}" name="customer">
                                                <div> <span class="font-weight-bold" style="margin-left: 10px;font-weight: bold">{{$customer->name}}</span></div>
                                                <div class="text-muted">{{$customer->address->full}}</div>
                                            @else
                                                <p>List of customers</p>
                                            @endif
                                            <div class="click-to-change">Click to change</div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row mb-2">
                                    <label  class="col-md-3 form-label">DateTime</label>
                                    <div class="view_selected_date_time">
                                        <div class="row">
                                            <div class="col-4">
                                                <span class="date" style="margin-left: 30px;">{{date('d-m-Y')}}</span>
                                            </div>
                                            <div class="col-8" style="text-align: right">
                                                <span class="time_cont_nav active">
                                                    <span class="text-muted"> From:</span>
                                                    <span class="time_from">9:00 AM</span>
                                                </span>
                                                <spn class="time_cont_nav">
                                                    <span class="text-muted" style="margin-left: 10px;"> To:</span>
                                                    <span class="time_to">11:00 AM</span>
                                                </spn>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="date_wrapper outside">
                                        <div class="lines"></div>
                                    </div>
                                </div>
                                <hr/>
                                <div class="row">
                                    <p class="text-muted">Service info</p>
                                </div>
                                <div class="row mb-2">
                                    <label  class="col-md-3 form-label">Title</label>
                                    <div class="col-md-9">
                                        <select class="form-control form-select" id="select_service" name="service_id" onchange="change_service()">
                                            <option value="0">Chose service...</option>
                                            @foreach($services as $service)
                                                <option value="{{$service->id}}">{{$service->title}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="row mb-4">
                                    <label class="col-md-3 form-label">Description</label>
                                    <div class="col-md-9">
                                        <textarea class="form-control" placeholder="Description" name="description">{{old('description')}}</textarea>
                                    </div>
                                </div>
                                <div class="row mb-4">
                                    <label  class="col-md-3 form-label">Price</label>
                                    <div class="col-md-9">
                                        <input type="text" class="form-control" id="price" placeholder="$ 00.00" name="price" value="{{old('price')}}">
                                    </div>
                                </div>
                            </div>
                            <div class="card-footer">
                                <button type="submit" class="btn btn-success btn-block">Save</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>


@stop
@section('scripts')
    <script src="{{ URL::asset('assets/js/Drum.js')}}"></script>
    <script src="{{ URL::asset('assets/js/hammer.mini.js')}}"></script>
    <script>
        function change_service(){
            var select = $("#select_service");
        }
    </script>
    <script>
        $(document).ready(function () {
            $(".date_wrapper").drum();
            $('.time_cont_nav').click(function (){
                $('.time_cont_nav').removeClass('active');
                $(this).addClass('active');
            });

        });
    </script>
@endsection

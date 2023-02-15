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
                                    <div class="cont_time_from container-datepicker active">
                                        <input type="hidden" class="input_time_from" name="time_from" value="">
                                        <div class="view_selected_date_time">
                                            <div class="row">
                                                <div class="col-6">
                                                    <span class="text-muted"> From:</span>
                                                    <span class="date" style="margin-left: 30px;"></span>
                                                </div>
                                                <div class="col-6">
                                                    <span class="text-muted"> Time:</span>
                                                    <span class="time_from"></span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="date_wrapper outside">
                                            <div class="lines"></div>
                                        </div>
                                    </div>
                                    <div class="cont_time_to container-datepicker active" style="margin-top: 15px;">
                                        <input type="hidden" class="input_time_to" name="time_to" value="">
                                        <div class="view_selected_date_time">
                                            <div class="row">
                                                <div class="col-6">
                                                    <span class="text-muted"> To:</span>
                                                    <span class="date" style="margin-left: 30px;">{{date('M-d-Y')}}</span>
                                                </div>
                                                <div class="col-6">
                                                    <span class="text-muted"> Time:</span>
                                                    <span class="time_from">9:00 AM</span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="date_wrapper outside">
                                            <div class="lines"></div>
                                        </div>
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
            var dataPickerTo = null;
            var dataPickerFrom = $(".cont_time_from").DataPicker({
                onChange : function (dateTime){
                    if(dataPickerTo){
                        let newDate = new Date(dateTime.getTime() + 2*60*60*1000)
                        dataPickerTo.setDateTime(newDate);
                    }
                    $('.input_time_from').val(dateTime.toLocaleString());
                }
            });
            var dataPickerTo = $(".cont_time_to").DataPicker({
                onChange : function (dateTime){
                    $('.input_time_to').val(dateTime.toLocaleString());
                }
            });
            dataPickerTo.setDateTime(new Date(new Date().getTime() + 2*60*60*1000))

            $('.cont_time_to').removeClass('active');
            $('.view_selected_date_time').click(function (){
                $('.container-datepicker').removeClass('active');
                $(this).parent().addClass('active');
            });

        });
    </script>
@endsection

@extends('layout.main')

@section('css')
    <link href="{{ URL::asset('assets/plugins/edtimer/style.css')}}" rel="stylesheet" />
    <link href="{{ URL::asset('assets/plugins/typehead/jquery.typeahead.css')}}" rel="stylesheet" />
@endsection

@section('content')
   
    <div class="main-container container-fluid">
        <!-- PAGE-HEADER -->
        <div class="page-header">
            <h1 class="page-title">Update Appointment</h1>
        </div>
        <!-- PAGE-HEADER END -->
        <!-- CONTENT -->        
        <div class="row">
            <div class="col-lg-4 m-auto">
                <div class="card">
                    <form method="post" action="{{route('appointment.update',["appointment"=>$appointment])}}" >
                        @csrf
                        <div class="card-body">    
                            @include("layout/error-message")
                            
                            <div class="row mb-2">
                                <label  class="col-md-3 form-label">Customer</label>
                                <div class="col-md-9">
                                    <div class="content-customer-scheduling" onclick="openModal()">
                                        <input type="hidden" value="{{$appointment->address->id}}" name="address_id" id="input_address_id">
                                        <div> <span class="font-weight-bold" style="font-weight: bold" id="customer_name">{{$appointment->customer->name}}</span></div>
                                        <div class="text-muted" id="customer_address">{{$appointment->address->full}}</div>
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
                                        <div class="timePicker" style="display: flex;justify-content: center"></div>
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
                                        <div class="timePicker" style="display: flex;justify-content: center"></div>
                                    </div>
                                </div>
                            </div>
                            
                        </div>
                        <div class="card-footer">
                            <button class="btn btn-success" type="submit">Update</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="exampleModal"  role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Modal title</h5>
                    <button aria-label="Close" class="btn-close" data-bs-dismiss="modal" onclick="return false;"><span aria-hidden="true">×</span></button>
                </div>
                <div class="modal-body">
                    @foreach($appointment->customer->address as $address)
                        <div class="content-customer-scheduling" style="margin-top: 10px;" onclick="choiceAddress(this, {{$address->id}})">
                            <div><span class="font-weight-bold choice_customer_name" style="font-weight: bold">{{$appointment->customer->name}}</span></div>
                            <div class="text-muted choice_customer_address">{{$address->full}}</div>
                            <div class="click-to-change"></div>
                        </div>
                    @endforeach
                </div>
                <div class="modal-footer">
                    <button class="btn btn-light" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
@stop
@section('scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.18.1/moment.min.js"></script>
    <script src="https://hammerjs.github.io/dist/hammer.min.js"></script>
    <script src="{{ URL::asset('assets/plugins/edtimer/timer.mini.js')}}"></script>

    <script>
        $(document).ready(function () {
            
            let timeFromConteiner = $('.cont_time_from');
            let timeToConteiner = $('.cont_time_to');
            let timerTo = null;
            let timerFrom = timeFromConteiner.find('.timePicker').timerD({
                setTime : "{{ $appointment->start }}",
                onChange : function(newTime){
                    let mtime = moment(newTime);
                    $('.input_time_from').val(mtime.format('YYYY-MM-DD HH:mm'));
                    timeFromConteiner.find('.date').html(mtime.format('MMMM DD'));
                    timeFromConteiner.find('.time_from').html(mtime.format('hh:mm A'));
                    if(timerTo){
                        timerTo.setTime(mtime.add(2,'hour'));
                    }
                }
            });
            
            timerTo = timeToConteiner.find('.timePicker').timerD({
                setTime : "{{ $appointment->end }}",
                onChange : function(newTime){
                    let mtime = moment(newTime);
                    $('.input_time_to').val(mtime.format('YYYY-MM-DD HH:mm'));
                    timeToConteiner.find('.date').html(mtime.format('MMMM DD'))
                    timeToConteiner.find('.time_from').html(mtime.format('hh:mm A'))
                }
            });
            $('.cont_time_to').removeClass('active');
            $('.view_selected_date_time').click(function (){
                $('.container-datepicker').removeClass('active');
                $(this).parent().addClass('active');
            });
        });

        function openModal(){
            $('#exampleModal').modal('show');
        }

        function choiceAddress(d, address_id){
            $('#input_address_id').val(address_id)
            var address = $(d).find(".choice_customer_address").html();
            $("#customer_address").html(address);
            $('#exampleModal').modal('hide');
        }
    </script>
@endsection

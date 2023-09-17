@extends('layout.main')

@section('css')
    <link href="{{ URL::asset('assets/plugins/edtimer/style.css')}}" rel="stylesheet" />
    <link href="{{ URL::asset('assets/plugins/typehead/jquery.typeahead.css')}}" rel="stylesheet" />
@endsection

@section('content')
   
    <div class="main-container container-fluid">
        <!-- PAGE-HEADER -->
        <div class="page-header">
            <h1 class="page-title">Create Appointment</h1>
        </div>
        <!-- PAGE-HEADER END -->
        <!-- CONTENT -->
                    
        <form method="post" action="{{route('schedule.store')}}" >
            @csrf
            <div class="row justify-content-md-center">
                <div class="col-md-4">
                    <div class="card">
                        <div class="card-body">
                            
                            @include("layout/error-message")
                            <div class="conteiner">
                                <div class="row mb-2">
                                    <div class="col-md-3">
                                        <div class="card-title">Customer</div>
                                    </div>
                                    <div class="col-md-9">
                                        <div class="content-customer-scheduling" onclick="$('#exampleModal').modal('show')" >
                                            <input type="hidden" value="{{$customer->id}}" name="customer_id" id="input_customer_id">
                                            <input type="hidden" value="{{$customer->address->last()->id}}" name="address_id" id="address_id">
                                            <div> <span class="font-weight-bold" style="font-weight: bold" id="customer_name">{{$customer->name}}</span></div>
                                            <div class="text-muted" id="customer_address">{{$customer->address->last()->full}}</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="container time-picker-header">
                                <div class="card-title">Choose a time</div>
                                <div class="row timer-picker-row ">
                                    <input type="hidden" class="input_time_from" name="time_from" value="">
                                    <input type="hidden" class="input_time_to" name="time_to" value="">
                                    <div class="col-6 header-item active" data-active = "from">
                                        <div class="row align-items-center">
                                            <div class="col-4 time-picker-title">From:</div>
                                            <div class="col-8 time-picker-date-cont set-time-from">
                                                <div class="time-picker-date">Sep 24</div>
                                                <div class="time-picker-time">11:00 AM</div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-6 header-item " data-active = "to">
                                        <div class="row align-items-center">
                                            <div class="col-4 time-picker-title">To:</div>
                                            <div class="col-8 time-picker-date-cont set-time-to">
                                                <div class="time-picker-date">Sep 24</div>
                                                <div class="time-picker-time">11:00 AM</div>
                                            </div>
                                        </div>
                                    </div>
                                </div>                                
                            </div>
                            <div class="date_wrapper">
                                <div class="timePicker" style="display: flex;justify-content: center"></div>
                            </div>
                        </div>
                    </div>

                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title"><i class="fe fe-list"></i> Services</h3>
                        </div>
                        <div class="card-body">
                            <ul class="list-group list-group-flush services-list" id="services-list">
                        
                            </ul>
                            <div class="text-center">
                                <a href="#" onclick="$('#add_new_service_model').modal('show');return false;" class="text-secondary">+ add new service</a>
                            </div>
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
                            <div id='techs-cont'>
                                <div class="tech-line">
                                    <input type="hidden" name="tech_ids[]" value="{{ Auth::user()->id }}" class="tech-ids">
                                    <div class="media m-0 mt-0">
                                        <img class="avatar brround avatar-md me-3" alt="avatra-img" src="../../assets/images/users/18.jpg">
                                        <div class="media-body">
                                            <div class="row">
                                                <div class="col-10">
                                                    <a href="#" class="text-default fw-semibold">{{ Auth::user()->name }}</a>
                                                    <p class="text-muted ">
                                                        {{ Auth::user()->phone }}
                                                    </p>
                                                </div>
                                                <div class="col-2">
                                                    <a href="#" class="text-danger" style="font-size:18px;" onClick="removeTech(this);return false;"><i class="fa fa-trash"></i></a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card">
                        <div class="card-footer">
                            <button class="btn btn-success" type="submit">Create</button>
                        </div>
                    </div>
                </div>
            </div>
        </form>
          
    </div>

    {{--Add new service model--}}
    @include('layout.modals.add-service')
    @include('layout.modals.add-tech')

    <div class="modal fade" id="exampleModal"  role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Change the address</h5>
                    <button aria-label="Close" class="btn-close" data-bs-dismiss="modal" onclick="return false;"><span aria-hidden="true">×</span></button>
                </div>
                <div class="modal-body">
                    <ul class="list-group">
                    @foreach($customer->address as $address)
                        <li class="list-group-item list-group-item-action " style="cursor: pointer; color:#232323" onClick='choiseAddress(this,{{ $address->id }})'>{{ $address->full }} </li>
                    @endforeach
                    </ul>
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
            let timerTo = null;
            let nowActive = 'from';
            let timeToIsChanged = false;
            let timerFrom = $('.timePicker').timerD({
                onChange : function(newTime){
                    let mtime = moment(newTime);
                    if(nowActive == 'from'){
                        $('.input_time_from').val(mtime.format('YYYY-MM-DD HH:mm'));
                        viewSetTime($('.set-time-from'),mtime);
                        if(!timeToIsChanged){
                            $('.input_time_to').val(mtime.add(2,'hour').format('YYYY-MM-DD HH:mm'));
                            viewSetTime($('.set-time-to'),mtime);
                        }
                    } else if(nowActive == 'to'){
                        timeToIsChanged = true;
                        $('.input_time_to').val(mtime.format('YYYY-MM-DD HH:mm'));
                        viewSetTime($('.set-time-to'),mtime);
                    }
                }
            });
            function viewSetTime(conteiner,time){
                conteiner.find('.time-picker-date').html(time.format('MMM DD'))
                conteiner.find('.time-picker-time').html(time.format('hh:mm A'))
            }
            
            $('.header-item').click(function (){
                nowActive = $(this).attr('data-active');
                if(!timerFrom)
                    return;

                if(nowActive == 'to'){
                    var timeTo = $('.input_time_to').val()
                    timerFrom.setTime(timeTo);
                } else if(nowActive == 'from'){
                    var timeFrom = $('.input_time_from').val()
                    timerFrom.setTime(timeFrom);
                }
                $('.header-item').removeClass('active');
                $(this).addClass('active');
            });
        });

        function choiseAddress(d,id){
            $('#address_id').val(id)
            var address = $(d).html();
            $("#customer_address").html(address);
            $('#exampleModal').modal('hide');
        }
       
        function addNewService(){
            let title = $('#title').val();
            let price = $('#price').val();
            let description = $('#description').val();
            $('#services-list').append(
                            '<li class="list-group-item d-flex">'+
                                '<input type="hidden" name="service-prices[]" class = "service-prices" value="'+price+'">'+
                                '<input type="hidden" name="service-title[]" value="'+title+'">'+
                                '<input type="hidden" name="service-description[]"  value="'+description+'">'+
                                '<div class="service-item-loading adding">'+
                                    '<div class="spinner-border text-secondary me-2" role="status">'+
                                        '<span class="visually-hidden">Loading...</span>'+
                                    '</div>'+
                                '</div>'+
                                '<div>'+
                                    '<i class="task-icon bg-secondary"></i>'+
                                    '<h6 clas="fw-semibold">'+title+'<span class="text-muted fs-11 mx-2 fw-normal"> $'+price+'</span>'+
                                    '</h6>'+
                                    '<p class="text-muted fs-12">'+description+'</p>'+
                                '</div>'+
                                '<div class="ms-auto d-flex">'+
                                    
                                    '<a href="#" onclick="removeService(this)" class="text-muted"><span class="fe fe-trash-2"></span></a>'+
                                '</div>'+
                            '</li>');
            $('#title').val('');
            $('#price').val('');
            $('#description').val('');
            $('#add_new_service_model').modal('hide');
        }

        function removeService(d){
            $(d).parent().parent().remove();
        }

        
    </script>
    @include('service.typehead-script')
@endsection

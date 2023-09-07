@extends('layout.main')

@section('css')
    {{-- <link href="{{ URL::asset('assets/css/drum.css')}}" rel="stylesheet" /> --}}
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
                            
                            <div class="row mb-2">
                                <label  class="col-md-3 form-label">Customer</label>
                                <div class="col-md-9">
                                    <div class="content-customer-scheduling" onclick="$('#exampleModal').modal('show')" >
                                        <input type="hidden" value="{{$customer->id}}" name="customer_id" id="input_customer_id">
                                        <input type="hidden" value="{{$customer->address->last()->id}}" name="address_id" id="address_id">
                                        <div> <span class="font-weight-bold" style="font-weight: bold" id="customer_name">{{$customer->name}}</span></div>
                                        <div class="text-muted" id="customer_address">{{$customer->address->last()->full}}</div>
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
                                        <div class="timePicker" style="display: flex; justify-content: center"></div>
                                    </div>
                                </div>
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
            let timeFromConteiner = $('.cont_time_from');
            let timeToConteiner = $('.cont_time_to');
            let timerTo = null;
            let timerFrom = timeFromConteiner.find('.timePicker').timerD({
                onChange : function(newTime){
                    let mtime = moment(newTime);
                    $('.input_time_from').val(mtime.format('YYYY-MM-DD hh:mm'));
                    timeFromConteiner.find('.date').html(mtime.format('MMMM DD'));
                    timeFromConteiner.find('.time_from').html(mtime.format('hh:mm A'));
                    if(timerTo){
                        console.log('setImer')
                        timerTo.setTime(mtime.add(2,'hour'));
                    }
                }
            });
            
            timerTo = timeToConteiner.find('.timePicker').timerD({
                setTime : moment().add(2,'hour'),
                onChange : function(newTime){
                    let mtime = moment(newTime);
                    $('.input_time_to').val(mtime.format('YYYY-MM-DD hh:mm'));
                    timeToConteiner.find('.date').html(mtime.format('MMMM DD'))
                    timeToConteiner.find('.time_from').html(mtime.format('hh:mm A'))
                }
            })

            $('.cont_time_to').removeClass('active');
            $('.view_selected_date_time').click(function (){
                $('.container-datepicker').removeClass('active');
                $(this).parent().addClass('active');
            });

            function toSqlFormat(date){
                return date.getFullYear()+"-"+(date.getMonth()+1)+"-"+date.getDate()+" "+date.getHours()+":"+date.getMinutes()+":00";
            }
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

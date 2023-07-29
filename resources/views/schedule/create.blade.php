@extends('layout.main')

@section('css')
    <link href="{{ URL::asset('assets/css/drum.css')}}" rel="stylesheet" />
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
                        </div>
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
                            <div id="line-services-added" class="row">
                        
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
                    $('.input_time_from').val(toSqlFormat(dateTime));
                }
            });
            var dataPickerTo = $(".cont_time_to").DataPicker({
                onChange : function (dateTime){
                    $('.input_time_to').val(toSqlFormat(dateTime));
                }
            });
            dataPickerTo.setDateTime(new Date(new Date().getTime() + 2*60*60*1000))

            $('.cont_time_to').removeClass('active');
            $('.view_selected_date_time').click(function (){
                $('.container-datepicker').removeClass('active');
                $(this).parent().addClass('active');
            });

            function toSqlFormat(date){
                return date.getFullYear()+"-"+(date.getMonth()+1)+"-"+date.getDate()+" "+date.getHours()+":"+date.getMinutes()+":00";
            }
        });

        // function openModal(){
        //     $('#exampleModal').modal('show');
        // }

        function choiseAddress(d,id){
            $('#address_id').val(id)
            var address = $(d).html();
            $("#customer_address").html(address);
            $('#exampleModal').modal('hide');
        }

        function add_new_service(){
            let title = $('#title').val();
            let price = $('#price').val();
            let description = $('#description').val();
            $('#line-services-added').append(
                            '<div class="col-sm-12 col-md-6 mb-2">'+
                                '<input type="hidden" name="service-prices[]" class = "service-prices" value="'+price+'">'+
                                '<input type="hidden" name="service-title[]" value="'+title+'">'+
                                '<input type="hidden" name="service-description[]"  value="'+description+'">'+
                                '<div class="cont-service-block">'+
                                    '<div class="row mb-2">'+
                                        '<div class="col-9"><b>'+title+'</b></div>'+
                                        '<div class="col-3"><b>$'+price+'</b></div>'+
                                    '</div>'+
                                    '<div class="hr"></div>'+
                                    '<div class="row mt-2">'+
                                        '<div class="col-9 iems-descrition">'+description+'</div>'+
                                        '<div class="col-3">'+
                                            '<p class="text-end">'+
                                                '<a href="#"onclick="removeServiceItem(this); return false;" class=" text-danger"><i class="fa fa-trash"></i></a>'+
                                            '</p>'+
                                        '</div>'+
                                    '</div>'+
                                '</div>'+
                            '</div>');
            $('#title').val('');
            $('#price').val('');
            $('#description').val('');
        }

        
    </script>
    @include('service.typehead-script')
@endsection

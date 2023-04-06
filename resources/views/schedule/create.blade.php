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
                    <form method="post" action="{{route('schedule.store')}}" >
                        @csrf
                        <div class="card">
                            <div class="card-body">
                                @if($errors->any())
                                    @include("layout/error-message")
                                @endif
                                <div class="row mb-2">
                                    <label  class="col-md-3 form-label">Customer</label>
                                    <div class="col-md-9">
                                        <div class="content-customer-scheduling" onclick="openModal()">
                                            @if(isset($customer))
                                                <input type="hidden" value="{{$customer->id}}" name="customer" id="input_customer_id">
                                                <div> <span class="font-weight-bold" style="margin-left: 10px;font-weight: bold" id="customer_name">{{$customer->name}}</span></div>
                                                <div class="text-muted" id="customer_address">{{$customer->address->full}}</div>
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
                            </div>
                            <div class="card-footer">
                                <button class="btn btn-success" type="submit">Create</button>
                            </div>
                        </div>
                        <div class="card">
                            <div class="card-body">
                                <h5 class="card-title">Services</h5>
                                <div id="line-services-added" class="row">
                            
                                </div>
                                <div class="row row-space">
                                    <div class="col-md-7">
                                        <div class="input-group custom">
                                            <input class="custom-input" type="text" placeholder="TITLE" id="title">
                                        </div>
                                    </div>
                                    <div class="col-md-5">
                                        <div class="input-group custom">
                                            <input class="custom-input" type="number" placeholder="PRICE" id="price">
                                        </div>
                                    </div>
                                </div>
                                <div class="input-group custom">
                                    <textarea class="custom-input" placeholder="DESCRIPTION" id="description"></textarea>
                                </div>
                            </div>
                            <div class="card-footer">
                                <button class="btn btn-primary" onclick="add_new_service();return false;">Add</button>
                            </div>
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
                    @if(isset($customers))
                        @foreach($customers as $customer)
                            <div class="content-customer-scheduling" style="margin-top: 10px;" onclick="choiceCustomer(this, {{$customer->id}})">
                                <div><span class="font-weight-bold choice_customer_name" style="margin-left: 10px;font-weight: bold">{{$customer->name}}</span></div>
                                <div class="text-muted choice_customer_address">{{$customer->address->full}}</div>
                                <div class="click-to-change"></div>
                            </div>
                        @endforeach
                    @endif
                </div>
                <div class="modal-footer">
                    <button class="btn btn-light" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary">Save changes</button>
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
                return date.getFullYear()+"-"+date.getMonth()+"-"+date.getDate()+" "+date.getHours()+":"+date.getMinutes()+":00";
            }

        });

        function openModal(){
            $('#exampleModal').modal('show');
        }

        function choiceCustomer(d, id){
            $('#input_customer_id').val(id)
            var name = $(d).find(".choice_customer_name").html();
            var address = $(d).find(".choice_customer_address").html();
            console.log(name,address)
            $("#customer_name").html(name)
            $("#customer_address").html(address);
            $('#exampleModal').modal('hide');
        }

        function add_new_service(){
            let title = $('#title').val();
            let price = $('#price').val();
            let description = $('#description').val();
            $('#line-services-added').append(
                            '<input type="hidden" name="service-prices[]" class = "service-prices" value="'+price+'">'+
                            '<input type="hidden" name="service-title[]" value="'+title+'">'+
                            '<input type="hidden" name="service-description[]"  value="'+description+'">'+
                            '<div class="col-sm-12 col-md-6 mb-2">'+
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
@endsection

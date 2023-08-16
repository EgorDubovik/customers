@extends('layout.main')

@section('content')

    <div class="main-container container-fluid">
        <!-- PAGE-HEADER -->
        <div class="page-header" style="margin-top: 10px; margin-bottom: 10px;">
            <h1 class="page-title">Customers</h1>
        </div>
        <!-- PAGE-HEADER END -->
        <!-- CONTENT -->
        <div class="row">
            <div class="col-md-6">

                {{--Serch bar--}}
                <div class="form-group">
                    <div class="row">
                        <div class="col-9">
                            <div class="input-group">
                                <input type="text" id="search_bar" class="form-control" placeholder="Search in customers">
                                <button class="btn btn-secondary" type="button" onclick="search_f($('#search_bar').val())">
                                    <i class="fa fa-search"></i>
                                </button>
                            </div>
                        </div>
                        <div class="col-3">
                            <a href="{{ route('customer.create') }}" class="btn btn-success w-100"><i class="fe fe-user-plus"></i> <span class="d-none d-lg-inline">New customer</span></a>
                        </div>
                    </div>
                </div>
               
                <div class="row" id="customer-list">
                    @forelse($customers as $customer)
                        <div class="col-xl-6">
                            <div class="card">
                                <div class="card-body" style="padding-bottom: 10px;">
                                    <div class="media m-0 mt-0">
                                        <div class="customer avatar avatar-md me-3">
                                            @if ($customer->appointments->last())
                                                {{ \Carbon\Carbon::parse($customer->appointments->last()->end)->formatLocalized('%b') }}  <br>
                                                {{ \Carbon\Carbon::parse($customer->appointments->last()->end)->format('d') }}
                                                
                                            @endif
                                            
                                        </div>
                                        <div class="media-body">
                                            <a href="{{route('customer.show',['customer'=>$customer->id])}}" class="text-default fw-semibold">{{$customer->name}}</a>
                                            <p class="text-muted ">
                                                {{$customer->phone}}
                                                <i class="fe fe-copy pull-right text-secondary" style="cursor: pointer"></i>
                                                <a href="#"> <i class="fe fe-phone-call pull-right" style="margin-right: 10px;"></i></a>
                                            </p>
                                        </div>
                                    </div>

                                    <div class="media-body border-bottom" style="padding-bottom: 10px;">
                                        <div class="d-flex align-items-center">
                                            <div class="mt-0">
                                                <h5 class="mb-1 fs-13 fw-semibold  " style="color: #1170e4 ">{{$customer->address->last()->full}}</h5>
                                            </div>
                                            <span class="ms-auto fs-14">
                                                <span class="float-end">
                                                    <span class="op-7 text-muted"><a href="#"> <i class="fe fe-map-pin"></i></a></span>
                                                </span>
                                            </span>
                                        </div>
                                    </div>
                                    <div class="row" style="padding-top: 5px;">
                                        <div class="col-8">
                                            @if ($customer->appointments->last())
                                                <small class="text-muted"> Last visit: {{ \Carbon\Carbon::parse($customer->appointments->last()->end)->diffForHumans()}}</small>
                                            @else
                                            <small class="text-muted"> No last visit</small>
                                            @endif
                                        </div>
                                        <div class="col-4 text-end">
                                            <small class="text-muted"> {{count($customer->appointments)}} visits </small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @empty
                    <div class="col">
                        <div class="alert alert-light text-center" role="alert">
                            <span class="alert-inner--text">List is eampty... <a style="margin-left: 20px;" href="{{ route('customer.create') }}">Create new customer</a></span>
                        </div>
                    </div>
                    @endforelse
                </div>
            </div>
            <div class="col-md-6 d-none d-sm-none d-md-block">
                <div class="row">
                    <div class="col-xl-6">
                        <div class="card">
                            <div class="row">
                                <div class="col-4">
                                    <div class="card-img-absolute circle-icon bg-primary text-center align-self-center box-primary-shadow bradius">
                                        <img src="../assets/images/svgs/circle.svg" alt="img" class="card-img-absolute">
                                        <i class="lnr lnr-user fs-30  text-white mt-4"></i>
                                    </div>
                                </div>
                                <div class="col-8">
                                    <div class="card-body p-4">
                                        <h2 class="mb-2 fw-normal mt-2">{{ count($customers) }}</h2>
                                        <h5 class="fw-normal mb-0">Total Customers</h5>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-6">
                        <div class="card">
                            <div class="row">
                                <div class="col-4">
                                    <div class="card-img-absolute circle-icon bg-secondary align-items-center text-center box-secondary-shadow bradius">
                                        <img src="../assets/images/svgs/circle.svg" alt="img" class="card-img-absolute">
                                        <i class="lnr lnr-briefcase fs-30 text-white mt-4"></i>
                                    </div>
                                </div>
                                <div class="col-8">
                                    <div class="card-body p-4">
                                        <h2 class="mb-2 fw-normal mt-2">{{ count($appointments) }}</h2>
                                        <h5 class="fw-normal mb-0">Total Appointments</h5>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-xl-6">
                        <div class="card overflow-hidden">
                            <div class="card-body">
                                <div class="d-flex">
                                    <div class="mt-2">
                                        <h6 class="">Total Cost</h6>
                                        <h2 class="mb-0 number-font">${{ number_format($sumCurentMonth,2,'.',' ') }}</h2>
                                    </div>
                                    <div class="ms-auto">
                                        <div class="chart-wrapper mt-1">
                                            <canvas id="costchart"
                                                class="h-8 w-9 chart-dropshadow"></canvas>
                                        </div>
                                    </div>
                                </div>
                                <span class="text-muted fs-12">
                                    <span class="text-{{ ($procent>=0) ? 'success' : 'danger' }}">
                                        <i class="fe fe-arrow-up-circle text-{{ ($procent>=0) ? 'success' : 'danger' }}"></i> {{ number_format($procent,2) }}%
                                    </span>
                                    Last month
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    {{-- <div class="add_new_cont">
        <a href="{{route('customer.create')}}" class="add_new_customer">+</a>
    </div> --}}
@stop
@section('scripts')
    <script src="{{ URL::asset('assets/plugins/chart/Chart.bundle.js')}}"></script>
    <script>
        var customers = @json($customers);
        var limit = 100;

        $('#search_bar').keyup(function (){
            var search = $(this).val();
            search_f(search);
        });

        function search_f(search){    
            if(search.length>=3){
                search = search.replace('(','\\(');
                search = search.replace(')','\\)');
                search = search.replace('+','\\+');
                var regex = new RegExp(search, "i");
                var search_phone = search.replace(/\D/g, "");
                    search_phone = (search_phone.length >2) ? new RegExp(search_phone, "i") : search_phone;
                var count = [];
                $.each(customers, function(key, val) {
                    var phone = val.phone.replace(/\D/g, "");

                    if (val.name.search(regex) != -1){
                        count.push(val);
                        return true;
                    }
                        
                    if(((phone.search(regex) !=-1) || (phone.search(search_phone) !=-1)) && search_phone.toString().length>2 ){
                        count.push(val);
                        return true;
                    }

                    $.each(val.address, function(key,address){
                        if (address.full.search(regex) != -1){
                            count.push(val);
                            return false;
                        }
                    });

                });
                viewSearchResult(count);
            } else if(search.length == 0){
                
                view_old_list();
            }
        }

        function viewSearchResult(list){
            if(list.length==0)
                s = "Empty";
            else s = "";
            for(var i = 0; i < list.length; i++){
                s+= getCustomerHTML(list[i]);
            }
            $('#customer-list').html(s);
        }

        function getCustomerHTML(customer){
           var block = '<div class="col-xl-6">'+
                '<div class="card">'+
                '<div class="card-body" style="padding-bottom: 10px;">'+
                '<div class="media m-0 mt-0">'+
                '<img class="avatar brround avatar-md me-3" alt="avatra-img" src="../assets/images/users/18.jpg">'+
                '<div class="media-body">'+
                '<a href="/customer/show/'+customer.id+'" class="text-default fw-semibold">'+customer.name+'</a>'+
                '<p class="text-muted ">'+
                customer.phone+
                '<i class="fe fe-copy pull-right text-secondary" style="cursor: pointer"></i>'+
                '<a href="#"> <i class="fe fe-phone-call pull-right" style="margin-right: 10px;"></i></a>'+
                '</p>'+
                '</div>'+
                '</div>'+
                '<div class="media-body border-bottom" style="padding-bottom: 10px;">'+
                '<div class="d-flex align-items-center">'+
                '<div class="mt-0">'+
                '<h5 class="mb-1 fs-13 fw-semibold  " style="color: #1170e4 ">'+customer.address[0].full+'</h5>'+
                '</div>'+
                '<span class="ms-auto fs-14">'+
                '<span class="float-end">'+
                '<span class="op-7 text-muted"><a href="#"> <i class="fe fe-map-pin"></i></a></span>'+
                '</span>'+
                '</span>'+
                '</div>'+
                '</div>'+
                '<div class="row" style="padding-top: 5px;">'+
                '<div class="col-8">'+
                '<small class="text-muted"> Last update: </small>'+
                '</div>'+
                '<div class="col-4 text-end">'+
                '<small class="text-muted"> 2 notes </small>'+
                '</div>'+
                '</div>'+
                '</div>'+
                '</div>'+
                '</div>';
            return block;
        }

        function view_old_list(){
            var s = "";
            for(var i = 0; i<customers.length; i++){
                if(i==limit) return;
                s+=getCustomerHTML(customers[i]);
            }
            $('#customer-list').html(s);
        }

        var ctx = document.getElementById('costchart').getContext('2d');
    ctx.height = 10;
    var myChart = new Chart(ctx, {
        type: 'line',
        data: {
            labels: ['Date 1', 'Date 2', 'Date 3', 'Date 4', 'Date 5', 'Date 6', 'Date 7', 'Date 8', 'Date 9', 'Date 10', 'Date 11', 'Date 12', 'Date 13', 'Date 14', 'Date 15', 'Date 16', 'Date 17'],
            datasets: [{
                label: 'Total Sales',
                data: [28, 56, 36, 32, 48, 54, 37, 58, 66, 53, 21, 24, 14, 45, 0, 32, 67, 49, 52, 55, 46, 54, 130],
                backgroundColor: 'transparent',
                borderColor: '#f7ba48',
                borderWidth: '2.5',
                pointBorderColor: 'transparent',
                pointBackgroundColor: 'transparent',
            }, ]
        },
        options: {
            maintainAspectRatio: false,
            legend: {
                display: false
            },
            responsive: true,
            tooltips: {
                enabled: false,
            },
            scales: {
                xAxes: [{
                    categoryPercentage: 1.0,
                    barPercentage: 1.0,
                    barDatasetSpacing: 0,
                    display: false,
                    barThickness: 5,
                    gridLines: {
                        color: 'transparent',
                        zeroLineColor: 'transparent'
                    },
                    ticks: {
                        fontSize: 2,
                        fontColor: 'transparent'
                    }
                }],
                yAxes: [{
                    display: false,
                    ticks: {
                        display: false,
                    }
                }]
            },
            title: {
                display: false,
            },
        }
    });
    // COST CHART CLOSEDvar ctx = document.getElementById('costchart').getContext('2d');
    ctx.height = 10;
    var myChart = new Chart(ctx, {
        type: 'line',
        data: {
            labels: ['Date 1', 'Date 2', 'Date 3', 'Date 4', 'Date 5', 'Date 6', 'Date 7', 'Date 8', 'Date 9', 'Date 10', 'Date 11', 'Date 12', 'Date 13', 'Date 14', 'Date 15', 'Date 16', 'Date 17'],
            datasets: [{
                label: 'Total Sales',
                data: [28, 56, 36, 32, 48, 54, 37, 58, 66, 53, 21, 24, 14, 45, 0, 32, 67, 49, 52, 55, 46, 54, 130],
                backgroundColor: 'transparent',
                borderColor: '#f7ba48',
                borderWidth: '2.5',
                pointBorderColor: 'transparent',
                pointBackgroundColor: 'transparent',
            }, ]
        },
        options: {
            maintainAspectRatio: false,
            legend: {
                display: false
            },
            responsive: true,
            tooltips: {
                enabled: false,
            },
            scales: {
                xAxes: [{
                    categoryPercentage: 1.0,
                    barPercentage: 1.0,
                    barDatasetSpacing: 0,
                    display: false,
                    barThickness: 5,
                    gridLines: {
                        color: 'transparent',
                        zeroLineColor: 'transparent'
                    },
                    ticks: {
                        fontSize: 2,
                        fontColor: 'transparent'
                    }
                }],
                yAxes: [{
                    display: false,
                    ticks: {
                        display: false,
                    }
                }]
            },
            title: {
                display: false,
            },
        }
    });
    </script>
@endsection

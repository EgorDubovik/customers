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
                    <div class="input-group">
                        <input type="text" id="search_bar" class="form-control" placeholder="Search in customers">
                        <button class="btn btn-secondary" type="button" onclick="search_f($('#search_bar').val())">
                            <i class="fa fa-search"></i>
                        </button>
                    </div>
                </div>

                <div class="row" id="customer-list">
                    @foreach($customers as $customer)
                        <div class="col-xl-6">
                            <div class="card">
                                <div class="card-body" style="padding-bottom: 10px;">
                                    <div class="media m-0 mt-0">
                                        <img class="avatar brround avatar-md me-3" alt="avatra-img" src="../assets/images/users/18.jpg">
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
                                                <h5 class="mb-1 fs-13 fw-semibold  " style="color: #1170e4 ">{{$customer->address->full}}</h5>
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
                                            <small class="text-muted"> Last update: {{$customer->updated_at->diffForHumans()}}</small>
                                        </div>
                                        <div class="col-4 text-end">
                                            <small class="text-muted"> {{count($customer->notes)}} notes </small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
            <div class="col-md-6 d-none d-sm-none d-md-block">
                <div class="row">
                    <div class="col-md-6">
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
                    <div class="col-md-6">
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
                                        <h2 class="mb-2 fw-normal mt-2">10,257</h2>
                                        <h5 class="fw-normal mb-0">Total Services</h5>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="add_new_cont">
        <a href="{{route('customer.create')}}" class="add_new_customer">+</a>
    </div>
@stop
@section('scripts')
    <script>
        var customers = @json($customers);
        var limit = 10;

        $('#search_bar').keyup(function (){
            var search = $(this).val();
            search_f(search);
        });

        function search_f(search){
            if(search.length>=3){
                search = search.replace('(','\\(');
                search = search.replace(')','\\)');
                search = search.replace('+','\\+');
                console.log(search);
                var regex = new RegExp(search, "i");
                var count = [];
                $.each(customers, function(key, val) {
                    var phone = val.phone.replace(/\D/g, "");
                    var search_phone = new RegExp(search.replace(/\D/g, ""), "i");
                    if ((val.name.search(regex) != -1) 
                        || (val.address.full.search(regex) != -1) 
                        || (val.phone.search(regex) !=-1) 
                        || (phone.search(regex) !=-1)
                        || (phone.search(search_phone) !=-1)){
                        count.push(val);
                    }
                });
                viewSearchResult(count);
            } else if(search.length == 0){
                console.log('view old list')
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
                '<h5 class="mb-1 fs-13 fw-semibold  " style="color: #1170e4 ">'+customer.address.full+'</h5>'+
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
    </script>
@endsection

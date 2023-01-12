@extends('layout.main')

@section('content')

    <div class="main-container container-fluid">
        <!-- PAGE-HEADER -->
        <div class="page-header">
            <h1 class="page-title">Customers</h1>
        </div>
        <!-- PAGE-HEADER END -->
        <!-- CONTENT -->
        <div class="row">
            <div class="col-md-6">
                <div class="row">
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
                                                <h5 class="mb-1 fs-13 fw-semibold  " style="color: #1170e4 ">{{$customer->address->full()}}</h5>
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
                                            <small class="text-muted"> 2 notes </small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
            <div class="col-md-6 d-none d-sm-none d-md-block">
                <div class="card">
                    <div class="card-body">
                        All customers: <b>{{count($customers)}}</b>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="add_new_cont">
        <a href="{{route('customer.create')}}" class="add_new_customer">+</a>
    </div>
@stop

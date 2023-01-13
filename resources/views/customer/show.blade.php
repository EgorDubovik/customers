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
                <div class="card">
                    <div class="card-header">Customer information</div>
                    <div class="card-body">
                        <p>
                            <span class="text-muted">Customer name:</span>
                            <span style="margin-left: 15px;">{{$customer->name}}</span>
                        </p>
                        <p>
                            <span class="fs-14 fw-bold">{{$customer->address->full()}}</span>
                            <i class="fe fe-copy pull-right text-secondary" style="cursor: pointer"></i>
                            <a href="#"> <i class="fe fe-map-pin pull-right" style="margin-right: 10px;"></i></a>
                        </p>
                        <p>
                            <span class="fs-14 text-info fw-bold">{{$customer->phone}}</span>
                            <i class="fe fe-copy pull-right text-secondary" onclick="copy_to({{$customer->phone}})" style="cursor: pointer"></i>
                            <a href="#"> <i class="fe fe-phone-call pull-right" style="margin-right: 10px;"></i></a>
                        </p>
                        <p>
                            <span class="fs-14 text-black">{{$customer->email}}</span>
                            <i class="fe fe-send pull-right text-secondary" style="cursor: pointer"></i>
                        </p>
                    </div>

                </div>
            </div>
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">Notes history</div>
                    <div class="card-body">
                        <ul class="task-list">
                            <li class="d-sm-flex">
                                <div>
                                    <i class="task-icon bg-primary"></i>
                                    <h6 class="fw-semibold"><span class="fs-14 mx-2 fw-normal">09 July 2021</span></h6>
                                    <p class="text-muted fs-15">Adam Berry finished task on</p>
                                </div>
                            </li>
                            <li class="d-sm-flex">
                                <div>
                                    <i class="task-icon bg-primary"></i>
                                    <h6 class="fw-semibold"><span class="fs-14 mx-2 fw-normal">09 July 2021</span></h6>
                                    <p class="text-muted fs-15">Adam Berry finished task on</p>
                                </div>
                            </li>
                            <li class="d-sm-flex">
                                <div>
                                    <i class="task-icon bg-primary"></i>
                                    <h6 class="fw-semibold"><span class="fs-14 mx-2 fw-normal">09 July 2021</span></h6>
                                    <p class="text-muted fs-15">Adam Berry finished task on</p>
                                </div>
                            </li>

                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop

@section('scripts')
    <script>
        function copy_to(text){
            navigator.clipboard.writeText(text);
        }
    </script>
@endsection

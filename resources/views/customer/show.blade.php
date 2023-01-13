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
                @include('layout/success-message',['status' => 'success'])
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Customer information</h3>
                        <div class="card-options">
                            <a href="{{route('customer.edit',['customer' => $customer])}}">
                                <i class="fe fe-edit text-success"></i>
                            </a>
                        </div>
                    </div>
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
                <div class="card">
                    <div class="card-header">Customer Tags <a class="fs-16 text-orange" style="margin-left: 20px;" data-bs-toggle="modal" href="#add_new_tag_model"><i class="fe fe-plus-circle"></i> </a></div>
                    <div class="card-body">
                        <div class="tags-row mb-3">
                            @foreach($customer->tags as $tag)
                                <span class="tag tag-rounded tag-icon tag-orange">{{$tag->title}} <a href="javascript:void(0)" class="tag-addon tag-addon-cross tag-orange"><i class="fe fe-x text-white m-1"></i></a></span>
                            @endforeach
                        </div>
                        <div class="row">
                            <select class="form-control form-select">
                                @foreach(\Illuminate\Support\Facades\Auth::user()->company_tags as $tags)
                                    <option>{{$tag->title}}</option>
                                @endforeach
                            </select>
                        </div>
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

    {{--Add new tag model--}}
    <div class="modal fade" id="add_new_tag_model" aria-hidden="true">
        <div class="modal-dialog modal-sm text-center" role="document">
            <div class="modal-content modal-content-demo">
                <form method="post" action="{{route('tag.store')}}">
                    @csrf
                    <div class="modal-header">
                        <h6 class="modal-title">Add new tag</h6><button aria-label="Close" class="btn-close" data-bs-dismiss="modal"><span aria-hidden="true">×</span></button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" name="customer_id" value="{{$customer->id}}">
                        <input class="form-control mb-4" placeholder="Tag title" name="title" type="text">
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-success" type="submit">Add</button> <button class="btn btn-light" data-bs-dismiss="modal">Close</button>
                    </div>
                </form>
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

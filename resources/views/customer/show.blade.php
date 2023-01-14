@extends('layout.main')

@section('content')

    <div class="main-container container-fluid">
        <!-- CONTENT -->
        <div class="row" style="padding-top: 20px;">
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
                            <span class="fs-14 fw-bold">{{$customer->address->full}}</span>
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
                                <span class="tag tag-rounded tag-icon tag-orange">{{$tag->title}} <a href="{{route('tag.untie',[$customer,$tag])}}" class="tag-addon tag-addon-cross tag-orange"><i class="fe fe-x text-white m-1"></i></a></span>
                            @endforeach
                        </div>
                        <div class="row">
                            <form method="post" action="{{route('tag.assign',['customer' => $customer])}}">
                                @csrf
                                <div class="input-group">
                                    <select class="form-control form-select" name="tag_id">
                                        @foreach(\Illuminate\Support\Facades\Auth::user()->company_tags as $tag)
                                            <option value="{{$tag->id}}">{{$tag->title}}</option>
                                        @endforeach
                                    </select>
                                    <div class="input-group-append">
                                        <button class="btn btn-outline-primary" type="submit" id="button-addon2">Assign</button>
                                    </div>
                                </div>

                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">Notes history</div>
                    <div class="card-body">
                        <form method="post" action="{{route('note.store', ['customer'=>$customer])}}">
                            @csrf
                            <div class="form-group">
                                <div class="input-group">
                                    <input type="text" id="search_bar" class="form-control" placeholder="Add new note to customer" name="text">
                                    <button class="btn btn-secondary" type="send">
                                        <i class="fa fa-save"></i>
                                    </button>
                                </div>
                            </div>
                        </form>
                        <ul class="task-list">
                            @foreach($customer->notes as $note)
                            <li class="d-sm-flex">
                                <div>
                                    <i class="task-icon bg-primary"></i>
                                    <h6 class="fw-semibold"><span class="fs-14 mx-2 fw-normal text-muted">{{$note->updated_at->diffForHumans()}}</span></h6>
                                    <p class=" fs-15">{{$note->text}}</p>
                                </div>
                            </li>
                            @endforeach
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

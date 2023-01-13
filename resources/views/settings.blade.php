@extends('layout.main')

@section('content')
    <div class="main-container container-fluid">

        <!-- PAGE-HEADER -->
        <div class="page-header">
            <h1 class="page-title">Settings</h1>
        </div>
        <!-- PAGE-HEADER END -->

        <!-- ROW-1 OPEN -->
        <div class="row">
            @if($errors->any())
                @include("layout/error-message")
            @endif
            <div class="col-xl-4">
                <div class="card">
                    <div class="card-header">Tags <a class="fs-16 text-orange" style="margin-left: 20px;" data-bs-toggle="modal" href="#add_new_tag_model"><i class="fe fe-plus-circle"></i> </a></div>
                    <div class="card-body">
                        @foreach(\Illuminate\Support\Facades\Auth::user()->company_tags as $tag)
                            <span class="tag tag-rounded tag-icon tag-orange">{{$tag->title}} <a href="{{route('tag.delete',['tag'=>$tag])}}" class="tag-addon tag-addon-cross tag-orange"><i class="fe fe-x text-white m-1"></i></a></span>
                        @endforeach
                    </div>
                </div>

                <div class="card panel-theme">
                    <div class="card-header">
                        <div class="float-start">
                            <h3 class="card-title">Card 2</h3>
                        </div>
                        <div class="clearfix"></div>
                    </div>
                    <div class="card-body no-padding">

                    </div>
                </div>
            </div>
            <div class="col-xl-8">
                <div class="card">

                </div>
            </div>
        </div>
        <!-- ROW-1 CLOSED -->
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
                        <input class="form-control mb-4" placeholder="Tag title" name="title" type="text">
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-success" type="submit">Add</button>
                        <button class="btn btn-light" onclick="return false;" data-bs-dismiss="modal">Close</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@stop

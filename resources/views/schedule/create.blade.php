@extends('layout.main')

@section('content')

    <div class="main-container container-fluid">
        <!-- PAGE-HEADER -->
        <div class="page-header">
            <h1 class="page-title">Create Appointment</h1>
        </div>
        <!-- PAGE-HEADER END -->
        <!-- CONTENT -->
        <div class="row">
            <div class="col-12">
                <div class="col-md-4 m-auto">
                    <div class="card">
                        <form method="post" >
                            @csrf
                            <div class="card-body">
                                @if($errors->any())
                                    @include("layout/error-message")
                                @endif
                                <div class="row mb-2">
                                    <label  class="col-md-3 form-label">Title</label>
                                    <div class="col-md-9">
                                        <input type="text" class="form-control" placeholder="Title" name="title" value="{{old('title')}}">
                                    </div>
                                </div>
                                <div class="row mb-4">
                                    <label class="col-md-3 form-label">Description</label>
                                    <div class="col-md-9">
                                        <textarea class="form-control" placeholder="Description" name="description">{{old('description')}}</textarea>
                                    </div>
                                </div>
                                <div class="row mb-4">
                                    <label  class="col-md-3 form-label">Price</label>
                                    <div class="col-md-9">
                                        <input type="text" class="form-control" id="price" placeholder="$ 00.00" name="price" value="{{old('price')}}">
                                    </div>
                                </div>
                            </div>
                            <div class="card-footer">
                                <button type="submit" class="btn btn-success btn-block">Save</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop


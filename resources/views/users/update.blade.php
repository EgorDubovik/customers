@extends('layout.main')

@section('content')

    <div class="main-container container-fluid">
        <!-- PAGE-HEADER -->
        <div class="page-header">
            <h1 class="page-title">Update user</h1>
            <div>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="javascript:void(0)">Apps</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Update user</li>
                </ol>
            </div>
        </div>
        <!-- PAGE-HEADER END -->
        <!-- CONTENT -->
        <div class="row">
            <div class="col-12">
                <div class="col-xl-6 m-auto">
                    <div class="card">
                        <div class="card-header">
                            <h4>Update user</h4>
                        </div>
                        <div class="card-body">
                            @if($errors->any())
                                @include("layout/error-message")
                            @endif
                            <form method="post">
                                @csrf
                                <div class="row mb-4">
                                    <label for="inputName" class="col-md-3 form-label">User Name</label>
                                    <div class="col-md-9">
                                        <input type="text" class="form-control" id="inputName" placeholder="Name" name="user_name" value="{{$user->name}}">
                                    </div>
                                </div>
                                <div class="row mb-4">
                                    <label for="inputEmail3" class="col-md-3 form-label">Email</label>
                                    <div class="col-md-9">
                                        <input type="email" class="form-control" id="inputEmail3" placeholder="Email" name="email" value="{{$user->email}}">
                                    </div>
                                </div>
                                <div class="row mb-4">
                                    <label for="inputEmail4" class="col-md-3 form-label">Phone</label>
                                    <div class="col-md-9">
                                        <input type="phone" class="form-control" id="inputEmail4" placeholder="Phone" name="phone" value="{{$user->phone}}">
                                    </div>
                                </div>

                                <hr>
                                <div class="mb-4 row">
                                    <div class="col-md-3"><b>Role</b></div>
                                    <div class="col-md-9">
                                        <div class="row">
                                            <label class="custom-control custom-checkbox">
                                                <input type="checkbox" class="custom-control-input" name="role[]" value="1" {{(in_array(\App\Models\Role::ADMIN,$role_array)) ? "checked" : ""}}>
                                                <span class="custom-control-label">Admin</span>
                                            </label>
                                            <footer class="blockquote-footer">Дает полное право на управление всем</footer>
                                        </div>
                                        <div class="row">
                                            <label class="custom-control custom-checkbox">
                                                <input type="checkbox" class="custom-control-input" name="role[]" value="2" {{(in_array(\App\Models\Role::FORMAN,$role_array)) ? "checked" : ""}}>
                                                <span class="custom-control-label">Forman</span>
                                            </label>
                                            <footer class="blockquote-footer">Дает право создавать inventory list и просматривать его</footer>
                                        </div>
                                        <div class="row">
                                            <label class="custom-control custom-checkbox">
                                                <input type="checkbox" class="custom-control-input" name="role[]" value="3" {{(in_array(\App\Models\Role::DRIVER,$role_array)) ? "checked" : ""}}>
                                                <span class="custom-control-label">Driver</span>
                                            </label>
                                            <footer class="blockquote-footer">Дает право только просматривать inventory list</footer>
                                        </div>
                                        <div class="row">
                                            <label class="custom-control custom-checkbox">
                                                <input type="checkbox" class="custom-control-input" name="role[]" value="4" {{(in_array(\App\Models\Role::HELPER,$role_array)) ? "checked" : ""}}>
                                                <span class="custom-control-label">Helper</span>
                                            </label>
                                            <footer class="blockquote-footer">Дает право только просматривать inventory list</footer>
                                        </div>
                                    </div>
                                </div>
                                <button type="submit" class="btn btn-warning btn-block">Update</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop

@extends('layout.main')

@section('content')

    <div class="main-container container-fluid">
        <!-- PAGE-HEADER -->
        <div class="page-header">
            <h1 class="page-title">Create new user</h1>
            <div>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="javascript:void(0)">Apps</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Create new user</li>
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
                            <h4>Create new user</h4>
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
                                        <input type="text" class="form-control" id="inputName" placeholder="Name" name="user_name" value="{{old('user_name')}}">
                                    </div>
                                </div>
                                <div class="row mb-4">
                                    <label for="inputEmail3" class="col-md-3 form-label">Email</label>
                                    <div class="col-md-9">
                                        <input type="email" class="form-control" id="inputEmail3" placeholder="Email" name="email" value="{{old('email')}}">
                                    </div>
                                </div>
                                <div class="row mb-4">
                                    <label for="inputEmail4" class="col-md-3 form-label">Phone</label>
                                    <div class="col-md-9">
                                        <input type="phone" class="form-control" id="inputEmail4" placeholder="Phone" name="phone" value="{{old('phone')}}">
                                    </div>
                                </div>
                                <div class="row mb-4">
                                    <label for="inputEmail4" class="col-md-3 form-label">Password</label>
                                    <div class="col-md-9">
                                        <input type="password" class="form-control" id="inputEmail4" placeholder="Password" name="password">
                                    </div>
                                </div>
                                <div class="row mb-4">
                                    <label for="inputEmail4" class="col-md-3 form-label">Re-enter Password</label>
                                    <div class="col-md-9">
                                        <input type="password" class="form-control" id="inputEmail4" placeholder="Re-enter Password" name="password2">
                                    </div>
                                </div>
                                <hr>
                                <div class="mb-4 row">
                                    <div class="col-md-3"><b>Role</b></div>
                                    <div class="col-md-9">
                                        <div class="row">
                                            <label class="custom-control custom-checkbox">
                                                <input type="checkbox" class="custom-control-input" name="role[]" value="1">
                                                <span class="custom-control-label">Admin</span>
                                            </label>
                                            <footer class="blockquote-footer">Дает полное право на управление всем</footer>
                                        </div>
                                        <div class="row">
                                            <label class="custom-control custom-checkbox">
                                                <input type="checkbox" class="custom-control-input" name="role[]" value="2">
                                                <span class="custom-control-label">Forman</span>
                                            </label>
                                            <footer class="blockquote-footer">Дает право создавать inventory list и просматривать его</footer>
                                        </div>
                                        <div class="row">
                                            <label class="custom-control custom-checkbox">
                                                <input type="checkbox" class="custom-control-input" name="role[]" value="3">
                                                <span class="custom-control-label">Driver</span>
                                            </label>
                                            <footer class="blockquote-footer">Дает право только просматривать inventory list</footer>
                                        </div>
                                        <div class="row">
                                            <label class="custom-control custom-checkbox">
                                                <input type="checkbox" class="custom-control-input" name="role[]" value="4">
                                                <span class="custom-control-label">Helper</span>
                                            </label>
                                            <footer class="blockquote-footer">Дает право только просматривать inventory list</footer>
                                        </div>
                                    </div>
                                </div>
                                <button type="submit" class="btn btn-success btn-block">Create</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop

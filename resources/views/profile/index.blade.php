@extends('layout.main')

@section('content')
<div class="main-container container-fluid">

    <!-- PAGE-HEADER -->
    <div class="page-header">
        <h1 class="page-title">Edit Profile</h1>
        <div>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="javascript:void(0)">Pages</a></li>
                <li class="breadcrumb-item active" aria-current="page">Edit Profile</li>
            </ol>
        </div>
    </div>
    <!-- PAGE-HEADER END -->

    <!-- ROW-1 OPEN -->
    <div class="row">
        @if($errors->any())
            @include("layout/error-message")
        @endif
        <div class="col-xl-4">
{{--            <div class="card">--}}
{{--                <form method="post" action="/profile/change/logo">--}}
{{--                    @csrf--}}
{{--                <div class="card-header">--}}
{{--                    <div class="card-title">Company Logo</div>--}}
{{--                </div>--}}
{{--                <div class="card-body">--}}
{{--                    <div class="text-center chat-image mb-5">--}}
{{--                        <div class="avatar avatar-xxl chat-profile mb-3 brround">--}}
{{--                            <a class="" href="profile.html"><img alt="avatar" src="{{ URL::asset('assets/images/users/7.jpg')}}" class="brround"></a>--}}
{{--                        </div>--}}

{{--                        <div class="main-chat-msg-name">--}}
{{--                            <a href="profile.html">--}}
{{--                                <h5 class="mb-1 text-dark fw-semibold">{{Auth::user()->company_name}}</h5>--}}
{{--                            </a>--}}
{{--                            <p class="text-muted mt-0 mb-0 pt-0 fs-13">{{(Auth::user()->is_mover) ? "Mover" : ""}} {{(Auth::user()->is_driver) ? "Driver" : ""}}</p>--}}
{{--                        </div>--}}

{{--                    </div>--}}
{{--                    <div class="avatar-list text-center">--}}
{{--                        <a href="#" class="avatar avatar-lg  brround cover-image" data-bs-image-src="../assets/images/users/12.jpg" ></a>--}}
{{--                        <a href="#" class="avatar avatar-lg  brround cover-image" data-bs-image-src="../assets/images/users/13.jpg" ></a>--}}
{{--                        <a href="#" class="avatar avatar-lg  brround cover-image" data-bs-image-src="../assets/images/users/11.jpg" ></a>--}}
{{--                        <a href="#" class="avatar avatar-lg  brround cover-image" data-bs-image-src="../assets/images/users/12.jpg" ></a>--}}
{{--                        <a href="#" class="avatar avatar-lg  brround cover-image" data-bs-image-src="../assets/images/users/14.jpg" ></a>--}}
{{--                    </div>--}}
{{--                </div>--}}
{{--                <div class="card-footer text-end">--}}
{{--                    <button type="submit" class="btn btn-success my-1">Save</button>--}}
{{--                </div>--}}
{{--                </form>--}}
{{--            </div>--}}

            <div class="card">
                <form action="/profile/change/password" method="post">
                    @csrf
                <div class="card-header">
                    <div class="card-title">Edit Password</div>
                </div>
                <div class="card-body">
                    @include('layout.success-message',['status'=>'successful_password'])
                    @include('layout.modify-errors',['status'=>'error_password'])
                    <div class="form-group">
                        <label class="form-label">Current Password</label>
                        <div class="wrap-input100 validate-input input-group" id="Password-toggle">
                            <a href="javascript:void(0)" class="input-group-text bg-white text-muted">
                                <i class="zmdi zmdi-eye text-muted" aria-hidden="true"></i>
                            </a>
                            <input class="input100 form-control" type="password" name="old_password" placeholder="Current Password">
                        </div>

                    </div>
                    <div class="form-group">
                        <label class="form-label">New Password</label>
                        <div class="wrap-input100 validate-input input-group" id="Password-toggle1">
                            <a href="javascript:void(0)" class="input-group-text bg-white text-muted">
                                <i class="zmdi zmdi-eye text-muted" aria-hidden="true"></i>
                            </a>
                            <input class="input100 form-control" type="password" name="password" placeholder="New Password">
                        </div>
                        <!-- <input type="password" class="form-control" value="password"> -->
                    </div>
                    <div class="form-group">
                        <label class="form-label">Confirm Password</label>
                        <div class="wrap-input100 validate-input input-group" id="Password-toggle2">
                            <a href="javascript:void(0)" class="input-group-text bg-white text-muted">
                                <i class="zmdi zmdi-eye text-muted" aria-hidden="true"></i>
                            </a>
                            <input class="input100 form-control" type="password" name="password2" placeholder="Confirm Password">
                        </div>
                        <!-- <input type="password" class="form-control" value="password"> -->
                    </div>
                </div>
                <div class="card-footer text-end">
                    <button type="submit" class="btn btn-primary">Update</button>
                </div>
                </form>
            </div>
            <div class="card panel-theme">
                <div class="card-header">
                    <div class="float-start">
                        <h3 class="card-title">Contact</h3>
                    </div>
                    <div class="clearfix"></div>
                </div>
                <div class="card-body no-padding">
                    <ul class="list-group no-margin">
                        <li class="list-group-item d-flex ps-3">
                            <div class="social social-profile-buttons me-2">
                                <a class="social-icon text-primary" href="javascript:void(0)"><i class="fe fe-mail"></i></a>
                            </div>
                            <a href="javascript:void(0)" class="my-auto">{{Auth::user()->email}}</a>
                        </li>
                        <li class="list-group-item d-flex ps-3">
                            <div class="social social-profile-buttons me-2">
                                <a class="social-icon text-primary" href="javascript:void(0)"><i class="fe fe-globe"></i></a>
                            </div>
                            <a href="javascript:void(0)" class="my-auto">www.abcd.com</a>
                        </li>
                        <li class="list-group-item d-flex ps-3">
                            <div class="social social-profile-buttons me-2">
                                <a class="social-icon text-primary" href="javascript:void(0)"><i class="fe fe-phone"></i></a>
                            </div>
                            <a href="javascript:void(0)" class="my-auto">{{Auth::user()->phone}}</a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
        <div class="col-xl-8">
            <div class="card">
                <form method="post" action="/profile/edit">
                    @csrf
                <div class="card-header">
                    <h3 class="card-title">Edit Profile</h3>
                </div>
                <div class="card-body">
                    @include('layout.success-message',['status'=>'successful_edit'])
                    @include('layout.modify-errors',['status'=>'error_edit'])
                    <div class="row">
                        <div class="col-lg-6 col-md-12">
                            <div class="form-group">
                                <label for="exampleInputname">User name</label>
                                <input type="text" class="form-control" id="exampleInputname" placeholder="User name" name="user_name" value="{{Auth::user()->name}}">
                            </div>
                        </div>
                        <div class="col-lg-6 col-md-12">
                            <div class="form-group">
                                <label for="exampleInputname1">Phone number</label>
                                <input type="text" class="form-control" id="exampleInputname1" placeholder="(555) 555-5555" name="phone" value="{{Auth::user()->phone}}">
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="exampleInputEmail1">Email address</label>
                        <input type="email" class="form-control" id="exampleInputEmail1" placeholder="Email address" name="email" value="{{Auth::user()->email}}">
                    </div>
{{--                    <div class="row">--}}
{{--                        <div class="col-lg-6 col-md-12">--}}
{{--                            <div class="form-group">--}}
{{--                                <label for="exampleInputname">MC number</label>--}}
{{--                                <input disabled type="text" class="form-control disable" name="mc_number" value="{{Auth::user()->mc}}">--}}
{{--                            </div>--}}
{{--                        </div>--}}
{{--                        <div class="col-lg-6 col-md-12">--}}
{{--                            <div class="form-group">--}}
{{--                                <label for="exampleInputname1">DOT number</label>--}}
{{--                                <input type="text" class="form-control disable" disabled name="dot_number" value="{{Auth::user()->dot}}" >--}}
{{--                            </div>--}}
{{--                        </div>--}}
{{--                    </div>--}}

{{--                    <div class="form-group">--}}
{{--                        <label class="form-label">Website</label>--}}
{{--                        <input class="form-control" name="website" value="{{Auth::user()->website}}" placeholder="http://splink.com">--}}
{{--                    </div>--}}
                </div>
                <div class="card-footer text-end">
                    <button type="submit" class="btn btn-success my-1">Update</button>
                </div>
                </form>
            </div>

            <div class="card">
                <div class="card-header">
                    <div class="card-title">Delete Account</div>
                </div>
                <div class="card-body">
                    <p>Its Advisable for you to request your data to be sent to your Email.</p>
                    <label class="custom-control custom-checkbox mb-0">
                        <input type="checkbox" class="custom-control-input" name="example-checkbox1" value="option1" checked>
                        <span class="custom-control-label">Yes, Send my data to my Email.</span>
                    </label>
                </div>
                <div class="card-footer text-end">
                    <a href="javascript:void(0)" class="btn btn-primary my-1">Deactivate</a>
                    <a href="javascript:void(0)" class="btn btn-danger my-1">Delete Account</a>
                </div>
            </div>
        </div>
    </div>
    <!-- ROW-1 CLOSED -->
</div>

@stop

@extends('layout.main')

@section('content')

    <div class="main-container container-fluid">
        <!-- PAGE-HEADER -->
        <div class="page-header">
            <h1 class="page-title">Users list</h1>
            <div>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="javascript:void(0)">Apps</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Users list</li>
                </ol>
            </div>
        </div>
        <!-- PAGE-HEADER END -->
        <!-- CONTENT -->
        <div class="row">
            <div class="col-12">
                @include('layout/success-message',['status' => 'successful'])
                <div class="card">
                    <div class="card-header">
                        Users list <a href="/users/create" class="btn btn-success" style="margin-left: 20px;"><i class="fa fa-plus"></i> Add new user</a>
                    </div>
                    <div class="card-body">
                        <table class="table border text-nowrap text-md-nowrap table-striped mb-0">
                            <thead>
                            <tr>
                                <th>ID</th>
                                <th>Date create</th>
                                <th>User name</th>
                                <th>Email</th>
                                <th>Phone</th>
                                <th>Roles</th>
                                <th>Actions</th>
                            </tr>
                            </thead>
                            <tbody>
                            @forelse($users as $user)
                                <tr >
                                    <td class="align-middle">{{$user->id}}</td>
                                    <td class="align-middle">{{\Carbon\Carbon::parse($user->created_at)->diffForHumans()}}</td>
                                    <td class="align-middle">{{$user->name}}</td>
                                    <td class="align-middle">{{$user->email}}</td>
                                    <td class="align-middle">{{$user->phone}}</td>
                                    <td class="align-middle">
                                        @foreach($user->roles as $role)
                                            <span class="tag tag-{{\App\Models\Role::TAGS[$role->role]}}">{{\App\Models\Role::ROLES[$role->role]}}</span>

                                        @endforeach

                                    </td>
                                    <td class="align-middle" style="width: 200px">
                                        @can('update-users', $user)
                                            <form method="post" action="/users/deactivate/{{$user->id}}" onsubmit="deactivate(this);return false;">
                                                @csrf
                                                @method('delete')
                                                <button type="submit" class="btn btn-danger"><i class="fa fa-trash"></i> <span class="d-none d-lg-inline">remove</span></button>
                                                <a href="/users/update/{{$user->id}}" class="btn btn-warning"><i class="fe fe-edit"></i> <span class="d-none d-lg-inline">update</span></a>
                                            </form>
                                        @endcan
                                    </td>

                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
        function deactivate(f){
            if(confirm('Are you sure?'))
                f.submit();
        }
    </script>
@stop

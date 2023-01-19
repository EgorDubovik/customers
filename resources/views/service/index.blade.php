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
                <div class="card col-md-8">
                    <div class="card-header">
                        Services list <a href="{{route('service.create')}}" class="btn btn-success" style="margin-left: 20px;"><i class="fa fa-plus"></i> Add new user</a>
                    </div>
                    <div class="card-body">
                        <table class="table border table-striped mb-0">
                            <thead>
                            <tr>
                                <th>Title</th>
                                <th>Description</th>
                                <th>Price</th>
                                <th>Actions</th>
                            </tr>
                            </thead>
                            <tbody>
                            @forelse($services as $service)
                                <tr >
                                    <td>{{$service->title}}</td>
                                    <td>{{$service->description}}</td>
                                    <td>$ {{$service->price}}</td>
                                    <td class="align-middle" style="width: 150px">
                                        @can('update-service', $service)
                                            <form method="post" action="{{route('service.delete',['service' => $service])}}" onsubmit="deactivate(this);return false;">
                                                @csrf
                                                @method('delete')
                                                <button type="submit" class="btn btn-danger"><i class="fa fa-trash"></i> </button>
                                                <a href="{{route('service.edit',['service'=>$service])}}" class="btn btn-warning"><i class="fe fe-edit"></i></a>
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

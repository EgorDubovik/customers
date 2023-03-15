@extends('layout.main')

@section('content')

    <div class="main-container container-fluid">
        <!-- PAGE-HEADER -->
        <div class="page-header">
            <h1 class="page-title">Services</h1>

        </div>
        <!-- PAGE-HEADER END -->
        <!-- CONTENT -->
        <div class="row">
            <div class="col-12">
                @include('layout/success-message',['status' => 'successful'])
                <div class="card col-md-8">
                    <div class="card-header">
                        Invoices <a href="{{route('invoice.create')}}" class="btn btn-success" style="margin-left: 20px;"><i class="fa fa-plus"></i> Send new invoice</a>
                    </div>
                    <div class="card-body">
                        <table class="table border table-striped mb-0">
                            <thead>
                            <tr>
                                <th>Title</th>
                                <th>Customer name</th>
                                <th>Email</th>
                                <th>Created at</th>
                                <th>Actions</th>
                            </tr>
                            </thead>
                            <tbody>
                            
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

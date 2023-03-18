@extends('layout.main')

@section('content')

    <div class="main-container container-fluid">
        <!-- PAGE-HEADER -->
        <div class="page-header">
            <h1 class="page-title">Invoices</h1>

        </div>
        <!-- PAGE-HEADER END -->
        <!-- CONTENT -->
        <div class="row">
            <div class="col-md-8">
                @include('layout/success-message',['status' => 'successful'])
                <div class="card">
                    <div class="card-header">
                        Invoices <a href="{{route('invoice.create')}}" class="btn btn-success" style="margin-left: 20px;"><i class="fa fa-plus"></i> Send new invoice</a>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive push">
                            <table class="table table-bordered table-hover mb-0 text-nowrap">
                                <tbody>
                                <tr>
                                    <th class="text-center">Id</th>
                                    <th>Customer</th>
                                    <th>Email</th>
                                    <th>Created at</th>
                                    <th class="text-end">Actions</th>
                                </tr>
                                
                                
                                    @foreach ($invoices as $invoice)
                                        <tr>
                                            <td>{{ $invoice->id }}</td>
                                            <td>
                                                {{ $invoice->customer_name }}
                                                <address class="text-muted">
                                                    2901 Ridgeview dr, Plano TX 75025<br>
                                                    edservicetx@gmail.com
                                                </address>
                                            </td>
                                            <td>{{ $invoice->email }}</td>
                                            <td>{{ \Carbon\Carbon::parse($invoice->created_at)->diffForHumans() }}</td>
                                            <td>
                                                <a href="{{ route('invoice.show',['invoice' => $invoice->id]) }}" class="btn btn-info"><i class="fe fe-eye"></i> View</a>
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
    </div>
    <script>
        function deactivate(f){
            if(confirm('Are you sure?'))
                f.submit();
        }
    </script>
@stop

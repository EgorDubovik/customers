@extends('layout.main')

@section('content')

    <div class="main-container container-fluid">
        <!-- PAGE-HEADER -->
        <div class="page-header">
            <h1 class="page-title">Customers</h1>
        </div>
        <!-- PAGE-HEADER END -->
        <!-- CONTENT -->
        <div class="row">
            <div class="col-md-12  col-xl-6">

            </div>
        </div>
    </div>
    <div class="add_new_cont">
        <a href="{{route('customer.create')}}" class="add_new_customer">+</a>
    </div>
@stop

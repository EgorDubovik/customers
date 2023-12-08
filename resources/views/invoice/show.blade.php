@extends('layout.main')

@section('content')

    <div class="main-container container-fluid">
        <!-- PAGE-HEADER -->
        <div class="page-header">
            <h1 class="page-title">Create new invoice</h1>
        </div>
        <!-- PAGE-HEADER END -->
        <!-- CONTENT -->
        <div class="row">

            <div class="col-md-6 m-auto">
                <div class="card">
                    <div class="card-header">
                        <spam class="text-muted">Access by link</spam>
                        <input type="text" class="form-control" value="{{ route('invoice.view.PDF',['key' => ($invoice->key) ? $invoice->key : "null"]) }}" readonly>
                    </div>
                    <div class="card-body">
                        @include('invoice.layout.invoice',$invoice)
                    </div>
                    <div class="card-footer text-end">
                        <form method="post" action="{{ route('invoice.resend',['invoice' => $invoice]) }}">
                            @csrf
                            <div class="input-group">
                                <button type="submit" class="btn btn-secondary mb-1" ><i class="si si-paper-plane"></i> Send New Copy</button>
                                <input type="text" id="main-email"  class="form-control" value="{{ $invoice->email }}" name="email">
                            </div>
                        </form>
                    </div>
                 </div>
            </div>
        </div>
        
    </div>
    
@stop
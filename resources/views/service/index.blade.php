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
                {{-- @include('layout/success-message',['status' => 'successful']) --}}
                <livewire:services />
            </div>
        </div>
    </div>

    {{-- @include('layout.modals.add-service') --}}
@stop

@section('scripts')
    <script>
        window.addEventListener('close-modal', event => {
            $('#add_new_service_model').modal('hide');
        })
    </script>
@endsection
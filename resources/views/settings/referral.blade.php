@extends('layout.main')

@section('content')
    <div class="main-container container-fluid">

        <!-- PAGE-HEADER -->
        <div class="page-header">
            <h1 class="page-title">Settings Referral</h1>
        </div>
        <!-- PAGE-HEADER END -->

        <!-- ROW-1 OPEN -->
        <div class="row">
            
            @include("layout/error-message")
            @include('layout/success-message', ['status' => 'success'])

            <div class="col-md-6 m-auto">
               <div class="card">
                  <div class="card-body">
                     
                     <div class="row mt-3 mb-4">
                        <div class="col-6 " style="text-align: left">
                           <label class="custom-switch form-switch mb-0" style="cursor: pointer">
                              <input type="checkbox" name="custom-switch-radio" class="custom-switch-input" {{ 1 ? 'checked' : '' }} onclick="toogleActivate(this)">
                              <span class="custom-switch-indicator custom-switch-indicator-lg"></span>
                              <span class="custom-switch-description">Activate referral program</span>
                           </label>
                        </div>
                     </div>

                  </div>
               </div>
            </div>
        </div>
    </div>
@stop

@section("scripts")
   <script src="../assets/plugins/datatable/js/jquery.dataTables.min.js"></script>
   <script src="../assets/plugins/datatable/js/dataTables.bootstrap5.js"></script>
   <script src="../assets/plugins/datatable/js/dataTables.buttons.min.js"></script>
@endsection

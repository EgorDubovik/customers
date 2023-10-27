@extends('layout.main')

@section('content')
    <div class="main-container container-fluid">

        <!-- PAGE-HEADER -->
        <div class="page-header">
            <h1 class="page-title">Settings</h1>
        </div>
        <!-- PAGE-HEADER END -->

        <!-- ROW-1 OPEN -->
        <div class="row">
            
            @include("layout/error-message")
            @include('layout/success-message', ['status' => 'success'])

            <div class="col-md-6 m-auto">
               <div class="card">
                  <div class="card-body">
                     <div class="form-group">
                        <label class="custom-switch form-switch mb-0" style="cursor: pointer">
                           <input type="checkbox" name="custom-switch-radio" class="custom-switch-input" onclick="toogleActivate(this)">
                           <span class="custom-switch-indicator custom-switch-indicator-lg"></span>
                           <span class="custom-switch-description">Activate booking online</span>
                        </label>
                     </div>

                     <div class="link-conteiner">
                        @if($bookAppointment)
                           <div class="row">
                              <div class="col-10">
                                 <div class="input-group">
                                    <input type="text" class="form-control input-key" placeholder="" value="{{ Request::root().'/appointment/book/'.$bookAppointment->key }}" aria-label="Example text with button addon" aria-describedby="button-addon1">
                                    <button class="btn btn-light copy-key" type="button" id="button-addon2"><i class="fe fe-copy"></i></button>
                                 </div>
                              </div>
                              <div class="col-2">
                                 <a href="{{ route('settings.book-online.delete') }}" class="btn btn-danger"><i class="fe fe-trash"></i></a>
                              </div>
                           </div>
                        @else
                           <a href="{{ route('settings.book-online.create') }}" class="btn btn-primary">Create new link</a>
                        @endif
                     </div>
                  </div>
               </div>
            </div>

        </div>
    </div>
@stop

@section("scripts")
    <script>
         function toogleActivate(d){
            let radio = $(d);
            let status = radio.is(':checked');
            
         }

         $('.input-key').on('focus', function() {
            $(this).select();
         });
         $('.copy-key').click(function(){
            $('.input-key').select();

         })
    </script>
@endsection

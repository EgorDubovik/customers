@extends('layout.main')

@section('css')
    <link href="{{ URL::asset('assets/css/myCalendar.css')}}" rel="stylesheet" />
@endsection

@section('content')
<div class="main-container container-fluid">
    <!-- PAGE-HEADER -->
    <div class="page-header">
        <h2 class="page-title">Schedule calendar</h2>
    </div>
    <!-- PAGE-HEADER END -->
    <!-- CONTENT -->
    <div class="row">
        <div class="col-md-9">
            <div class="card">
                <div class="card-body">
                    <div id="calendar">

                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card">
                <div class="card-body">
                    <a href="{{ route('appointment.map') }}"><i class="fe fe-map-pin"></i> view all on map</a>
                </div>
            
            </div>
            <div class="card">
                <div class="card-header">Open appointments ({{ count($appointments->where('status',0)) }})</div>
                <div class="card-body">
                    @forelse ($appointments->where('status',0) as $open_appointment)  
                        @if ($open_appointment && $open_appointment->customer)
                            @include('schedule.inludes.open-appointments',['appointment' => $open_appointment])    
                        @endif              
                        
                    @empty
                        <div class="empty_open_appointments">You don`t have any open appointments</div>
                    @endforelse
                </div>
            </div>
        </div>
        
    </div>
    <!-- ROW-1 CLOSED -->
</div>
<div class="modal fade" id="smallmodal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-sm" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Change appointment time</h5>
                <button class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
            </div>
            <div class="modal-body">
                <p>Do you want change appointment time?</p>
            </div>
            <div class="modal-footer">
                <button class="btn btn-primary">Save changes</button>
                <button class="btn btn-light" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
@stop

@section('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.18.1/moment.min.js"></script>
<script src="{{ URL::asset('assets/js/ealena.mini.js')}}"></script>
    <script>
        $(document).ready(function(){
            $('#calendar').edEvents({
                startTime : '06:00',
                endTime: '21:00',
                dayOrWeek : ($(document).width() >= 576 ) ? 'week' : 'days',
                events : [
                    @foreach ($appointments as $appointment)
                        @if ($appointment && $appointment->customer)
                            {
                                title: '{{ $appointment->customer->name }}', 
                                startTime: '{{ $appointment->start }}', 
                                endTime: '{{ $appointment->end }}',
                                background: "{{ ($appointment->status == App\Models\Appointment::ACTIVE) ? ((count($appointment->techs) > 0) ? $appointment->techs[0]->color : '#1565C0') : '#ccc' }}",
                                href: "{{ route('appointment.show',['appointment'=>$appointment]) }}",
                                addClass : "{{ ($appointment->status == App\Models\Appointment::DONE) ? 'done' : 'pedding'}}",
                                id : {{ $appointment->id }},

                            },
                        @endif
                    @endforeach 
                ],
                onEndDrag : function(appointment){
                    // console.log(appointment);
                    // $('#smallmodal').modal('show');
                }
            });
        });
      </script>
@endsection

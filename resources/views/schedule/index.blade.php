@extends('layout.main')

@section('css')
    <link href="{{ URL::asset('assets/css/myCalendar.css')}}" rel="stylesheet" />
@endsection

@section('content')
<div class="main-container container-fluid">
    <!-- ROW-1 OPEN -->
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
                <div class="card-header">Open appointments ({{ count($appointments->where('status',0)) }})</div>
                <div class="card-body">
                    @forelse ($appointments->where('status',0) as $open_appointment)                
                        <a href="{{ route('appointment.show',['appointment'=>$open_appointment]) }}" class="a-open-appointment">
                            <div class="row open-app-item align-items-center">
                                <div class="col-2 tech-color"><div class="user_circule" style="background: {{ ((count($open_appointment->techs) > 0) ? $open_appointment->techs[0]->color : '#1565C0') }}"></div></div>
                                <div class="col-4">
                                    <div class="customer-name">{{ $open_appointment->customer->name }}</div>
                                    <div class="customer-phone">{{ $open_appointment->customer->phone }}</div>
                                </div>
                                <div class="col-6">
                                    <div class="customer-address">{{ $open_appointment->address->full }}</div>
                                </div>
                            </div>
                        </a>
                    @empty
                        <div class="empty_open_appointments">You don`t have any open appointments</div>
                    @endforelse
                </div>
            </div>
        </div>
        
    </div>
    <!-- ROW-1 CLOSED -->
</div>

@stop

@section('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.18.1/moment.min.js"></script>
<script src="{{ URL::asset('assets/js/appointmentScheduler.mini.js')}}"></script>
    <script>
        $(document).ready(function(){
            $('#calendar').appointmentScheduler({
                startTime : '06:00',
                endTime: '21:00',
                dayOrWeek : ($(document).width() >= 576 ) ? 'week' : 'days',
                appointments : [
                    @foreach ($appointments as $appointment)
                        {
                            title: '{{ $appointment->customer->name }}', 
                            startTime: '{{ $appointment->start }}', 
                            endTime: '{{ $appointment->end }}',
                            background: "{{ ($appointment->status == App\Models\Appointment::ACTIVE) ? ((count($appointment->techs) > 0) ? $appointment->techs[0]->color : '#1565C0') : '#ccc' }}",
                            href: "{{ route('appointment.show',['appointment'=>$appointment]) }}",
                            addClass : "{{ ($appointment->status == App\Models\Appointment::DONE) ? 'done' : 'pedding'}}",

                        },    
                    @endforeach 
                ],
                
            });
        });
      </script>
@endsection

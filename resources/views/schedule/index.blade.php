@extends('layout.main')

@section('css')
    <link href="{{ URL::asset('assets/css/myCalendar.css')}}" rel="stylesheet" />
@endsection

@section('content')
<div class="main-container container-fluid">
    <!-- ROW-1 OPEN -->
    <div class="row">
        <div class="col-md-3">
            <div class="card">
                <div class="card-header">Appointments for today</div>
            </div>
        </div>
        <div class="col-md-9">
            <div class="card">
                <div class="card-body">
                    <div id="calendar">

                    </div>
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
                appointments : [
                    @foreach ($appointments as $appointment)
                        {
                            title: '{{ $appointment->customer->name }}', 
                            startTime: moment('{{ $appointment->start }}'), 
                            endTime: moment('{{ $appointment->end }}'),
                            background: "#1565C0",
                            href: "{{ route('appointment.show',['appointment'=>$appointment]) }}",
                            status : 'pending',
                        },    
                    @endforeach 
                ],
                
            });
        });
      </script>
@endsection

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
                
                    // { startTime: moment('2023-06-01 09:00'),endTime:moment('2023-06-01 13:00') , title: 'Meeting 1', background : '#1565c0', status : 'pending'},
                    // { startTime: moment('2023-06-01 11:00'),endTime:moment('2023-06-01 13:00') , title: 'Meeting 2', background : '#1565c0', status : 'pending' },
                    // { startTime: moment('2023-06-01 13:00'),endTime:moment('2023-06-01 15:00') , title: 'Meeting 3', background : '#1565c0', status : 'pending' },
                    // { startTime: moment('2023-05-13 13:00'),endTime:moment('2023-05-13 15:00') , title: 'Meeting 4', background : '#1565c0', status : 'pending' },
                    // { startTime: moment('2023-06-04 13:00'),endTime:moment('2023-06-04 15:00') , title: 'Meeting 5', background : '#1565c0', status : 'pending' },
                    // { startTime: moment('2023-06-10 9:00'),endTime:moment('2023-06-10 11:00') , title: 'Meeting 7', background : '#1565c0', status : 'pending' },
                    // { startTime: moment('2023-06-05 13:00'),endTime:moment('2023-06-05 15:00') , title: 'Meeting 6', href : "?test", background : '#46c015', status : 'done' },
                ],
                
            });
            // var calendarEl = document.getElementById('calendar');
            // var calendar = new FullCalendar.Calendar(calendarEl, {
            //     slotMinTime: "07:00:00",
            //     slotMaxTime: "21:00:00",
            //     contentHeight: 'auto',
            //     height:500,
            //     displayEventEnd: true,
            //     headerToolbar: {
            //         left: 'prev,next today',
            //         center: 'title',
            //         right: 'dayGridMonth,timeGridWeek,timeGridDay'
            //     },
            //     events: [
            //         @foreach ($appointments as $appointment)
            //             {
            //                 title: '{{ $appointment->customer->name }}', 
            //                 start: '{{ $appointment->start }}', 
            //                 end: '{{ $appointment->end }}',
            //                 color: "#1565C0",
            //                 url: "{{ route('appointment.show',['appointment'=>$appointment]) }}",
                            
            //             },    
            //         @endforeach
            //     ],
            //     editable: true,
            //     eventClick: function(info) {
            //         if (info.event.url) {
            //             event.jsEvent.preventDefault();
            //             window.open(info.event.url);
                        
            //         }
            //     }
                
                
            // });
            // calendar.render();
        });
      </script>
@endsection

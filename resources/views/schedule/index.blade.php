@extends('layout.main')

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
<script src="{{ URL::asset('assets/plugins/fullcalendar/moment.min.js')}}"></script>
<script src="{{ URL::asset('assets/plugins/fullcalendar/fullcalendar.min.js')}}"></script>
    <script>
        $(document).ready(function(){
            var calendarEl = document.getElementById('calendar');
            var calendar = new FullCalendar.Calendar(calendarEl, {
                slotMinTime: "07:00:00",
                slotMaxTime: "21:00:00",
                contentHeight: 'auto',
                height:500,
                displayEventEnd: true,
                headerToolbar: {
                    left: 'prev,next today',
                    center: 'title',
                    right: 'dayGridMonth,timeGridWeek,timeGridDay'
                },
                events: [
                    @foreach ($appointments as $appointment)
                        {
                            title: '{{ $appointment->customer->name }}', 
                            start: '{{ $appointment->start }}', 
                            end: '{{ $appointment->end }}',
                            color: "#1565C0",
                            url: "{{ route('appointment.show',['appointment'=>$appointment]) }}",
                            
                        },    
                    @endforeach
                ],
                editable: true,
                eventClick: function(info) {
                    if (info.event.url) {
                        window.open(info.event.url);
                        return false;
                    }
                }
                
                
            });
            calendar.render();
        });
      </script>
@endsection

@extends('layout.main')

@section('content')
<div class="main-container container-fluid">

    <!-- PAGE-HEADER -->
    <div class="page-header">
        <h1 class="page-title">Edit Profile</h1>
        <div>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="javascript:void(0)">Pages</a></li>
                <li class="breadcrumb-item active" aria-current="page">Edit Profile</li>
            </ol>
        </div>
    </div>
    <!-- PAGE-HEADER END -->

    <!-- ROW-1 OPEN -->
    <div class="row">
        <div class="card">
            <div class="card-body">
                <div id="calendar">

                </div>
            </div>
        </div>
    </div>
    <!-- ROW-1 CLOSED -->
</div>

@stop

@section('scripts')
{{-- <script src="https://cdn.jsdelivr.net/npm/moment@2.22.1/min/moment-with-locales.min.js"></script> --}}

{{-- <script src="https://cdn.jsdelivr.net/npm/jquery-touchswipe@1.6.18/jquery.touchSwipe.min.js"></script> --}}
{{-- <script src="https://cdn.jsdelivr.net/npm/arrobefr-jquery-calendar-bs4@1.0.3/dist/js/jquery-calendar.min.js"></script> --}}
{{-- <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/arrobefr-jquery-calendar-bs4@1.0.3/dist/css/jquery-calendar.min.css"> --}}
<script src="{{ URL::asset('assets/plugins/fullcalendar/moment.min.js')}}"></script>
<script src="{{ URL::asset('assets/plugins/fullcalendar/fullcalendar.min.js')}}"></script>
    <script>
        $(document).ready(function(){
            var calendarEl = document.getElementById('calendar');
            var calendar = new FullCalendar.Calendar(calendarEl, {
                headerToolbar: {
                    left: 'prev,next today',
                    center: 'title',
                    right: 'dayGridMonth,timeGridWeek,timeGridDay'
                },
                events: [
                    { // this object will be "parsed" into an Event Object
                        title: 'The Title', 
                        start: '2023-03-26 10:00:00', 
                        end: '2018-03-26 12:00:00',
                        color: "#1565C0",
                        textColor: "red",
                    },
                    
                ],
                editable: true,
                
            });
            calendar.render();
            $('.fc-event-main').css('color','green');
        //   var now = moment();
        //   $('#calendar').Calendar({
        //     events: [
        //         { // An event on the current week on Wednesday from 10h to 12h
        //             start: new Date( 2023, 02, 26,06,00).getTime()/1000,
        //             end: new Date( 2023, 02, 26,08,00).getTime()/1000,
        //             title: 'An event title !',
        //             content: 'Hello World! <br>Foo Bar<p class="text-right">Wow this text is right aligned !</p>',
        //             category: 'Egor Dubovik',
        //             color: "#C62828",
                    
        //         },
        //         { // An event on the current week on Wednesday from 10h to 12h
        //             start: new Date( 2023, 02, 26,08,00).getTime()/1000,
        //             end: new Date( 2023, 02, 26,10,00).getTime()/1000,
        //             title: 'An event title !',
        //             content: 'Hello World! <br>Foo Bar<p class="text-right">Wow this text is right aligned !</p>',
        //             category: 'Alena Dubovik',
        //             color: "#1565C0",
        //         },
        //         { 
        //             start: new Date( 2023, 02, 26,8,00).getTime()/1000,
        //             end: new Date( 2023, 02, 26,10,00).getTime()/1000,
        //             title: 'An event title !',
        //             content: 'Hello World! <br>Foo Bar<p class="text-right">Wow this text is right aligned !</p>',
        //             category: 'Egor Dubovik',
        //             color: "#C62828",
                    
        //         },
        //     ]
        //   }).init();
        });
      </script>
@endsection

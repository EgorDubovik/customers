<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <meta http-equiv="X-UA-Compatible" content="ie=edge">
   <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
   <link href="{{ URL::asset('assets/css/book-appointment.css')}}" rel="stylesheet" />
   <link href="{{ URL::asset('assets/plugins/edtimer/style.css')}}" rel="stylesheet" />
   <link href="{{ URL::asset('assets/plugins/datetime-picker/css/pignose.calendar.css')}}" rel="stylesheet" />
   <title>Book appointment online</title>
</head>
<body>
   @include('book-appointment.layout.header', ['company' => $company, 'title' => 'Your referral statistics'])
   <main>
      <div class="container mb-4">
         @include('layout.error-message')
         @php
            var_dump($stats);
         @endphp
      </div>
   </main>

   <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
   <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
   <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.18.1/moment.min.js"></script>
   <script src="{{ URL::asset('assets/plugins/datetime-picker/js/pignose.calendar.js') }}" ></script>
   <script src="https://hammerjs.github.io/dist/hammer.min.js"></script>
   <script src="{{ URL::asset('assets/plugins/edtimer/timer.mini.js')}}"></script>
</body>
</html>
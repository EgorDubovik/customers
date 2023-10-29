@component('mail::layout')
    {{-- Header --}}
    @slot('header')
        @component('mail::header', ['url' => config('app.url')])
            
        @endcomponent
    @endslot

   {{-- Body --}}
   <!-- Body here -->
   <p>{{ $appointment->customer->name}}, made appointment</p>
    
   <div class="appointment-time">
      {{ \Carbon\Carbon::createFromFormat('Y-m-d H:i:s',$appointment->start)->format('l, F d, Y') }}<br>
      {{ \Carbon\Carbon::createFromFormat('Y-m-d H:i:s',$appointment->start)->format('g:i A') }} - {{ \Carbon\Carbon::createFromFormat('Y-m-d H:i:s',$appointment->end)->format('g:i A') }}
   </div>
   <div class="appointment-services">
      @foreach ($appointment->services as $service)
         <div class="appointment-service">{{ $service->title }} - ${{ $service->price }}</div>
      @endforeach
   </div>
   <br><br>
   <div class="customer-address">
      {{ $appointment->address->line1 }}, {{ $appointment->address->line2 }}<br>
      {{ $appointment->address->city }}, {{ $appointment->address->state }} {{ $appointment->address->zip }}<br>
      {{ $appointment->customer->phone }}
   </div>
   
   {{-- Footer --}}
   @slot('footer')
      @component('mail::footer')
         {{ date('m-d-Y') }} - {{ env('APP_NAME') }}
      @endcomponent
   @endslot
@endcomponent
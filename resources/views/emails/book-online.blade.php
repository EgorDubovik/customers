@component('mail::layout')
    {{-- Header --}}
    @slot('header')
        @component('mail::header', ['url' => config('app.url')])
            
        @endcomponent
    @endslot

   {{-- Body --}}
   <!-- Body here -->
   <p>Dear {{ $appointment->customer->name}}, </p>
   <p>Thank you for booking an appointment with us.</p>
   <p>Your appointment has been successfully booked. Below are the details of your appointment:</p>
   
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

   <p style="margin-top:20px;">To view or manage your appointment use link below</p>
   <p style="margin-top:20px;text-align: center">
      <a style="background: #4772ff;color: #fff; padding: 10px; text-decoration: none;border-radius: 9px;" href="{{  env('BOOK_APP_BASE_URL').'/appointment/book/view/'.$key }}">Open appointment info</a>
   </p>
   {{-- Footer --}}
   @slot('footer')
      @component('mail::footer')
         {{ date('m-d-Y') }} - {{ $appointment->company->name ?? 'EDService ' }}
      @endcomponent
   @endslot
@endcomponent
<a href="{{ route('appointment.show',['appointment'=>$appointment]) }}" class="a-open-appointment">
   <div class="row open-app-item align-items-center" style="border-top: 3px solid {{ ((count($appointment->techs) > 0) ? $appointment->techs[0]->color : '#1565C0') }}">
       <div class="col-5">
           <div class="customer-name">{{ $appointment->customer->name }}</div>
           <div class="customer-phone">{{ $appointment->customer->phone }}</div>
       </div>
       <div class="col-7">
           <div class="customer-address">{{ $appointment->address->full }}</div>
       </div>
   </div>
</a>
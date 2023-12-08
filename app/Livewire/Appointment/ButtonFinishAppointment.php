<?php

namespace App\Livewire\Appointment;

use App\Models\Appointment;
use Livewire\Component;
use Illuminate\Support\Facades\Gate;

class ButtonFinishAppointment extends Component
{

    public $appointment;

    public function activateOrDiactivate(){
        Gate::authorize('update-remove-appointment',['appointment'=>$this->appointment]);

        $status = $this->appointment->status == Appointment::ACTIVE ? Appointment::DONE : Appointment::ACTIVE;
        $this->appointment->update([
            'status' => $status,
        ]);
    }

    public function render()
    {
        return view('livewire.appointment.button-finish-appointment');
    }
}
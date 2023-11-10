<?php

namespace App\Http\Livewire;

use App\Models\Appointment;
use Livewire\Component;

class ButtonFinishAppointment extends Component
{

    public $appointment;

    public function activateOrDiactivate(){
        $status = $this->appointment->status == Appointment::ACTIVE ? Appointment::DONE : Appointment::ACTIVE;
        $this->appointment->update([
            'status' => $status,
        ]);
    }

    public function render()
    {
        return view('livewire.button-finish-appointment');
    }
}

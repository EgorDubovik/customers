<?php

namespace App\Livewire\Appointment;

use App\Models\Appointment;
use Livewire\Component;
use Illuminate\Support\Facades\Gate;
use App\Models\Payment;
use Livewire\Attributes\On;
class ButtonFinishAppointment extends Component
{

    public $appointment;
    public $remainingBalance = 0;
    public $total = 0;

    public function activateOrDiactivate(){
        Gate::authorize('update-remove-appointment',['appointment'=>$this->appointment]);

        $status = $this->appointment->status == Appointment::ACTIVE ? Appointment::DONE : Appointment::ACTIVE;
        $this->appointment->update([
            'status' => $status,
        ]);
    }

    public function render()
    {
        $this->remainigBalance();
        return view('livewire.appointment.button-finish-appointment');
    }

    public function remainigBalance(){
        $tax = 0;
        $total = 0;
        $taxVal = 8.25;
        foreach($this->appointment->services as $service){
            if($service->taxable){
                $tax += round($service->price * ($taxVal / 100),2);
            }
            $total+=$service->price;
        }
        
        $payments = Payment::where('appointment_id',$this->appointment->id)->get()->sum('amount');
        $this->remainingBalance = round($total + $tax - $payments,2);
        $this->total = $total;
    }

    #[On('update-total')]
    public function refresh($total, $remainingBalance){
        // dd('refresh');
        $this->total = $total;
        $this->remainingBalance = $remainingBalance;
        $this->render();
    }
}

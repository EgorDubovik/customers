<?php

namespace App\Livewire;

use App\Models\Appointment;
use Livewire\Component;
use Illuminate\Support\Facades\Gate;
use App\Models\AppointmentTechs;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class TechBlock extends Component
{

    public $appointment;

    public function render()
    {
        $techs = User::where('company_id', Auth::user()->company_id)->get();
        return view('livewire.tech-block',['techs'=>$techs]);
    }

    public function add($tech_id){

        Gate::authorize('add-tech-to-appointment',['appointment'=>$this->appointment,'tech_id'=>$tech_id]);

        $techs = AppointmentTechs::where('appointment_id', $this->appointment->id)->pluck('tech_id')->toArray();
        
        if(!in_array($tech_id,$techs)){
            AppointmentTechs::create([
                'appointment_id' => $this->appointment->id,
                'tech_id'        => $tech_id,
                'creator_id'     => Auth::user()->id,
            ]);
        }

        $this->dispatch('close-modal');

    }

    public function delete($appointment_id, $tech_id){

        Gate::authorize('update-remove-appointment',['appointment'=>$this->appointment]);

        AppointmentTechs::where('appointment_id',$this->appointment->id)
            ->where('tech_id',$tech_id)
            ->delete();

        
    }
}

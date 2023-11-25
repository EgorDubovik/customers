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
    public $techs;
    public $appointment_techs;
    public $mode = "save";

    public function mount(){
        $this->refreshAppointmenttechs();
    }

    private function refreshAppointmenttechs(){
        $this->appointment_techs = $this->appointment->techs;
    }

    public function render()
    {
        $this->techs = User::where('company_id', Auth::user()->company_id)
            ->where('active','1')
            ->get();

        return view('livewire.tech-block',[
            'techs'=>$this->techs,
            'appointment_techs' => $this->appointment_techs
        ]);
    }

    public function add($tech_id){

        Gate::authorize('add-tech-to-appointment',['appointment'=>$this->appointment,'tech_id'=>$tech_id]);
        if(!$this->appointment_techs->contains('id',$tech_id)){
            if($this->mode == 'save'){
                AppointmentTechs::create([
                    'appointment_id' => $this->appointment->id,
                    'tech_id'        => $tech_id,
                    'creator_id'     => Auth::user()->id,
                ]);
                $this->refreshAppointmenttechs();
            } else {
                $this->appointment_techs[] = User::find($tech_id);
            }
            
        }

        $this->dispatch('close-modal');
    }

    public function delete($tech_id){

        Gate::authorize('update-remove-appointment',['appointment'=>$this->appointment]);

        AppointmentTechs::where('appointment_id',$this->appointment->id)
            ->where('tech_id',$tech_id)
            ->delete();

        $this->refreshAppointmenttechs();
    }
}

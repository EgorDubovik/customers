<?php

namespace App\Livewire\Appointment;

use App\Models\Appointment;
use App\Models\AppointmentService;
use Livewire\Component;
use Illuminate\Support\Facades\Gate;
use App\Models\Payment;

class Services extends Component
{

    public $mode = 'create';
    public $formTitle = 'Add new service';
    public Appointment $appointment;


    public $title;
    public $price;
    public $description;
    public $editableServiceId = null;

    public $remainingBalance = 250;

    public function mount(){
        $this->setRemainigBalance();
    }

    public function render()
    {
        return view('livewire.appointment.services');
    }


    public function store(){
        
        Gate::authorize('add-remove-service-from-appointment',[$this->appointment]);

        $appointmentService = AppointmentService::create([
            'appointment_id' => $this->appointment->id,
            'title' => $this->title,
            'description' => $this->description,
            'price' => $this->price,
        ]);
        $this->setRemainigBalance();

        $this->dispatch('close-modal');
    }

    public function delete(AppointmentService $service){
        Gate::authorize('add-remove-service-from-appointment',[$this->appointment]);
        
        if($service->appointment_id == $this->appointment->id)
            $service->delete();
        $this->setRemainigBalance();

    }

    public function edit(AppointmentService $service){

        if($service->appointment_id !== $this->appointment->id)
            return false;

        $this->editableServiceId = $service->id;
        $this->mode = 'edit';
        $this->formTitle = 'Edit service';
        $this->title = $service->title;
        $this->price = $service->price;
        $this->description = $service->description;
    }

    public function update(){

        Gate::authorize('add-remove-service-from-appointment',[$this->appointment]);

        if($this->editableServiceId==null)
            return false;
        
        $appointmentService = AppointmentService::find($this->editableServiceId);
        if(!$appointmentService || $appointmentService->appointment_id != $this->appointment->id)
            return false;

        $appointmentService->update([
            'title' => $this->title,
            'price' => $this->price,
            'description' => $this->description,
        ]);

        $this->setRemainigBalance();

        $this->dispatch('close-modal');
        
    }

    public function create(){
        $this->mode = 'create';
        $this->formTitle = 'Add new service';

        $this->title = "";
        $this->price = "";
        $this->description = "";
    }

    private function setRemainigBalance(){
        $this->remainingBalance = $this->appointment->services->sum('price') - Payment::where('appointment_id',$this->appointment->id)->get()->sum('amount');
    }
}

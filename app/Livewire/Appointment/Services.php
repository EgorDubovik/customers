<?php

namespace App\Livewire\Appointment;

use App\Models\AppointmentService;
use Livewire\Component;
use Illuminate\Support\Facades\Gate;
use App\Models\Payment;

class Services extends Component
{

    public $mode = 'save'; 
    public $formTitle = 'Add new service';
    public $appointment;

    public $title;
    public $price;
    public $description;
    public $isViewTaxable;
    public $isTaxable = false;
    public $editableServiceId = null;

    private $taxVal = 8.25;

    public $total = 0;
    public $tax = 0;
    public $remainingBalance;

    public function mount(){
        $this->isTaxable = $this->isTaxableSave();
    }

    public function render()
    {
        $this->setMoneyValue();
        return view('livewire.appointment.services');
    }

    public function store(){
        
        Gate::authorize('add-remove-service-from-appointment',[$this->appointment]);

        // Validate

        AppointmentService::create([
            'appointment_id' => $this->appointment->id,
            'title' => $this->title,
            'description' => $this->description,
            'price' => $this->price,
            'taxable' => $this->isTaxable,
        ]);

        $this->dispatch('close-modal');
    }

    public function delete(AppointmentService $service){

        Gate::authorize('add-remove-service-from-appointment',[$this->appointment]);
        
        if($service->appointment_id == $this->appointment->id)
            $service->delete();
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
        $this->isTaxable = ($service->taxable) ? true : false;
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
            'taxable' => $this->isTaxable,
        ]);

        $this->dispatch('close-modal');
        
    }

    public function create(){
        $this->mode = 'save';
        $this->formTitle = 'Add new service';

        $this->title = "";
        $this->price = "";
        $this->description = "";
        $this->isTaxable = $this->isTaxableSave();
    }

    private function isTaxableSave(){
        return true;
    }

    private function setMoneyValue(){
        $tax = 0;
        $total = 0;
        foreach($this->appointment->services as $service){
            if($service->taxable){
                $tax += round($service->price * ($this->taxVal / 100),2);
            }
            $total+=$service->price;
        }

        $this->tax = $tax;
        $this->total = $total + $tax;
        $this->remainingBalance = $this->getRemainigBalance() + $this->tax;
    }

    private function getRemainigBalance(){
        return $this->appointment->services->sum('price') - Payment::where('appointment_id',$this->appointment->id)->get()->sum('amount');
    }

}

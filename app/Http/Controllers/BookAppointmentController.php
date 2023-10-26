<?php

namespace App\Http\Controllers;

use App\Models\Addresses;
use App\Models\Appointment;
use App\Models\BookAppointment;
use App\Models\BookAppointmentProvider;
use App\Models\AppointmentService;
use App\Models\Customer;
use App\Models\Service;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Str;

class BookAppointmentController extends Controller
{
    public function index(Request $request, $key){
        $company = BookAppointment::where('key',$key)->first();
        if(!$company)
            return abort(404);
        
        return view('book-appointment.index',['company' => $company->company,'key' => $key]);
    }

    public function store(Request $request, $key){
        $company = BookAppointment::where('key',$key)->first();
        if(!$company)
            return abort(404);
        
        $request->validate([
            'phone_number' => 'required',
            'address_line1' => 'required',
        ],[
            'phone_number.required' => 'Please fill your phone number',
            'address_line1.required' => 'Please fill at least first line of address',
        ]);

        $customer = Customer::create([
            'name' => $request->name,
            'phone' => $request->phone_number,
            'company_id' => $company->id,
        ]);

        $address = Addresses::create([
            'line1' => $request->address_line1,
            'line2' => $request->address_line2,
            'city' => $request->city,
            'state' => $request->state,
            'zip' => $request->zip,
            'customer_id' => $customer->id,
        ]);

        $startTime = Carbon::createFromFormat('Y-m-d H:i',  $request->datetime);
        $endTime = $startTime->copy()->addHours(2);

        $appointment = Appointment::create([
            'customer_id' => $customer->id,
            'company_id' => $company->id,
            'address_id' => $address->id,
            'start' => $startTime,
            'end' => $endTime,
        ]);

        if($request->has('service')){
            foreach($request->input('service') as $key => $value){
                $company_service = Service::where('company_id',$company->id)
                                        ->where('id',$value)->first();
                if($company_service){                        
                    AppointmentService::create([
                        'appointment_id' => $appointment->id,
                        'title' => $company_service->title,
                        'description' => $company_service->description,
                        'price' => $company_service->price,
                    ]);
                }
            }
        }

        $key = Str::random(40);
        while(BookAppointmentProvider::where('key', $key)->exists()) {
            $key = Str::random(40);
        }
        
        $bookAppointmentProvider = BookAppointmentProvider::create([
            'appointment_id'    => $appointment->id,
            'key'               => $key,
        ]);
        
        return redirect('appointment/book/view/'.$bookAppointmentProvider->key);
    }

    public function view(Request $request, $key){

        $bookAppointmentProvider = BookAppointmentProvider::where('key',$key)->first();

        if(!$bookAppointmentProvider)
            abort(404);
        
        return view('book-appointment.show',['appointment'=>$bookAppointmentProvider->appointment,'key'=>$key]);
    }
}

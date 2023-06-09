<?php

namespace App\Http\Controllers;

use App\Models\Addresses;
use App\Models\Appointment;
use App\Models\Customer;
use App\Models\CustomerTags;
use App\Models\Tag;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

class CustomerController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        $customers = Customer::where('company_id',Auth::user()->company_id)
            ->orderBy('updated_at','DESC')
            ->get()
            ->makeHidden(['address_id','company_id', 'created_at','updated_at','notes']);

        return view('customer.index', ['customers'=>$customers]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('customer.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        $address = Addresses::create([
            'line1' => $request->line1,
            'line2' => $request->line2,
            'city' => $request->city,
            'state' => $request->state,
            'zip' => $request->zip,
        ]);

        $customer = Customer::create([
            'name' => $request->customer_name,
            'phone' => $request->customer_phone,
            'email' => $request->email,
            'address_id' => $address->id,
            'company_id' => Auth::user()->company_id,
        ]);

        return redirect()->route('customer.show',['customer'=>$customer])->with('success','Added successful');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Customer  $customer
     * @return \Illuminate\Http\Response
     */
    public function show(Customer $customer)
    {

        //!! Timezone from settings

        $this->authorize('view-customer',['customer'=>$customer]);
        $appointments = Appointment::where('customer_id', $customer->id)->get();
        $upcoming_appoinments = Appointment::where('customer_id', $customer->id)
                                            ->where('end','>', date('Y-m-d H:i:s'))
                                            ->get();
        return view('customer.show', ['customer'=>$customer, 
                                    'appointments' => $appointments,
                                    'upcoming_appoinments' => $upcoming_appoinments,
                                ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Customer  $customer
     * @return \Illuminate\Http\Response
     */
    public function edit(Customer $customer)
    {
        Gate::authorize('update-customer', $customer);
        return view('customer.edit', ['customer' => $customer]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Customer  $customer
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Customer $customer)
    {
        Gate::authorize('update-customer', $customer);
        $customer->address->update([
            'line1' => $request->line1,
            'line2' => $request->line2,
            'city' => $request->city,
            'state' => $request->state,
            'zip' => $request->zip,
        ]);

        $customer->update([
            'name' => $request->customer_name,
            'phone' => $request->customer_phone,
            'email' => $request->email,
        ]);

        return redirect()->route('customer.show', ['customer' => $customer])->with('success','Update customer have been successful');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Customer  $customer
     * @return \Illuminate\Http\Response
     */
    public function destroy(Customer $customer)
    {
        //
    }


}

<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Scheduler;
use App\Models\Service;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SchedulerController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $customers = Customer::where('company_id',Auth::user()->company_id)
            ->get();
        $services = Service::where('company_id', Auth::user()->compnany_id)
            ->get();
        return view('schedule.create',['customers' => $customers, 'services' => $services]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Scheduler  $scheduler
     * @return \Illuminate\Http\Response
     */
    public function show(Scheduler $scheduler)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Scheduler  $scheduler
     * @return \Illuminate\Http\Response
     */
    public function edit(Scheduler $scheduler)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Scheduler  $scheduler
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Scheduler $scheduler)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Scheduler  $scheduler
     * @return \Illuminate\Http\Response
     */
    public function destroy(Scheduler $scheduler)
    {
        //
    }
}

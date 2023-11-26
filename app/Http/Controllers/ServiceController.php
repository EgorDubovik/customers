<?php

namespace App\Http\Controllers;

use App\Models\Service;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

class ServiceController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $services = Service::where('company_id',Auth::user()->company_id)->get();
        return view('service.index',['services' => $services]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        Service::create([
           'title' => $request->title,
           'description' => $request->description,
           'price' => $request->price,
           'company_id' => Auth::user()->company_id,
        ]);
        return redirect()->route('service.index')->with('successful', 'Service has been created successful');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Service  $item
     * @return \Illuminate\Http\Response
     */
    public function show(Service $item)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Service  $item
     * @return \Illuminate\Http\Response
     */
    public function edit(Service $service)
    {
        return view('service.edit',['service' => $service]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Service  $item
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Service $service)
    {
        $service->update([
            'title' => $request->title,
            'description' => $request->description,
            'price' => $request->price,
        ]);
        return redirect()->route('service.index')->with('successful', 'Service has been created successful');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Service  $item
     * @return \Illuminate\Http\Response
     */
    public function destroy(Service $service)
    {
        Gate::authorize('update-service',['service'=>$service]);
        $service->delete();
        return back();
    }
}

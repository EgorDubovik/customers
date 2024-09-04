<?php

namespace App\Http\Controllers\Api\Job;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Job\Job;
use App\Models\Job\Service;

class JobServicesController extends Controller
{
    public function store(Request $request, $job_id){
        $job = Job::find($job_id);
        if(!$job)
            return response()->json(['error' => 'Job not found'], 404);

        $this->authorize('update-job', $job);

        $service = new Service();
        $service->fill($request->all());
        $service->job_id = $job->id;
        $service->save();

        return response()->json(['service' => $service], 201);
    }
}

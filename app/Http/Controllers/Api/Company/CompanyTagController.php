<?php

namespace App\Http\Controllers\Api\Company;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\CompanySettings\CompanyTag;

class CompanyTagController extends Controller
{
    public function index(){
        $companyTags = CompanyTag::where('company_id', auth()->user()->company_id)->get();
        return response()->json($companyTags);
    }

    public function store(Request $request){

        $this->authorize('create', CompanyTag::class);

        $request->validate([
            'title' => 'required',
            'color' => 'required',
        ]);

        $companyTag = new CompanyTag();
        $companyTag->title = $request->title;
        $companyTag->color = $request->color;
        $companyTag->company_id = auth()->user()->company_id;
        $companyTag->save();

        return response()->json($companyTag);
    }
}

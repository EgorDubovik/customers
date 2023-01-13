<?php

namespace App\Http\Controllers;

use App\Models\CustomerTags;
use App\Models\Tag;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TagController extends Controller
{
    public function store(Request $request){

        $tag = Tag::updateOrCreate([
           'title' => $request->title,
           'company_id' => Auth::user()->company_id,
        ]);

        if ($request->has('customer_id')){

            CustomerTags::updateOrCreate([
                'customer_id' => $request->customer_id,
                'tag_id' => $tag->id,
            ]);
        }

        return redirect()->back();
    }
}

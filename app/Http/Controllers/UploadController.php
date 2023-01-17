<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\ImageManagerStatic as Image;
use App\Models\Image as NewImage;

class UploadController extends Controller
{

    public function store(Customer $customer, Request $request){

        Gate::authorize('upload-images', ['customer' => $customer]);

        $request->validate([
            'images.*' => 'required|image|mimes:jpeg,png,jpg'
        ]);

        $pictures = $request->file('images');
        foreach ($request->file('images') as $picture) {
            $fileName = time() . '_' . $picture->getClientOriginalName();
            $image = Image::make($picture);
            if ($image->width() > env('UPLOAD_WIDTH_SIZE'))
                $image->resize(env('UPLOAD_WIDTH_SIZE'), null, function ($constraint) {
                    $constraint->aspectRatio();
                });
            $image->save(storage_path('app/public/images/'.$fileName));
            NewImage::create([
                'customer_id' => $customer->id,
                'path' => 'storage/images/' . $fileName,
                'owner_id' => Auth::user()->id,
            ]);
        }

        return redirect()->back()->with('status', 'Pictures uploaded successfully!');
    }

    public function view(Request $request, NewImage $image, $filename){
        Gate::authorize('show-images',['image' => $image]);
        return Image::make(storage_path('app/public/images/'.$filename))->response();
    }

    public function delete(Request $request, NewImage $image){
        Gate::authorize('delete-images',['image'=>$image]);
        Storage::disk('public')->delete('images/'.$image->file_name);
        $image->delete();
        return back();
    }

}

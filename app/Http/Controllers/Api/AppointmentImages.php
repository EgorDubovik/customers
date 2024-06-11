<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Appointment;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\ImageManagerStatic as Image;

class AppointmentImages extends Controller
{
   function store(Request $request, $appointment_id)
   {
      $request->validate([
         'image' => 'required|image|mimes:jpeg,png,jpg|max:2048',
      ]);

      $appointment = Appointment::find($appointment_id);

      $this->authorize('update-remove-appointment', $appointment);

      $filePath = 'images/app'.$appointment_id.'-' . time() . '_' . $request->image->hashName();


      $image = Image::make($request->image);
      if ($image->width() > env('UPLOAD_WIDTH_SIZE'))
         $image->resize(env('UPLOAD_WIDTH_SIZE'), null, function ($constraint) {
            $constraint->aspectRatio();
         });
      $image = $image->encode();

      $path = Storage::disk('s3')->put($filePath, $image);

      return response()->json(['success' => 'You have successfully uploaded the image.', 'path' => $filePath], 200);
   }
}

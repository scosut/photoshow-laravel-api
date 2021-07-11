<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use App\Photo;

class PhotosController extends Controller
{
  public function store(Request $request) {
    $data = json_decode($request->photo, true);
    $data = filter_var_array($data, FILTER_SANITIZE_STRING);
    $data['file'] = $request->file('file');

     $validator = Validator::make($data, [
      'text' => 'required',
      'description' => 'required',
      'file'  => 'required|image|max:500'
    ]);

    if ($validator->fails()) {
      return response()->json(["success" => false, "errors" => $validator->errors(), "message" => "failed validation", "data" => $data]);
    }

    // file upload
    $album_id = $data['album_id'];
    $filenameWithExt = $data['file']->getClientOriginalName();
    $filename = pathinfo($filenameWithExt, PATHINFO_FILENAME);
    $extension = $data['file']->getClientOriginalExtension();
    $filesize = $data['file']->getClientSize();
    $currentTime = time();
    $filenameToStore = "{$filename}_{$currentTime}.{$extension}";
    $path = $data['file']->storeAs("public/photos/$album_id", $filenameToStore);

    $data['title'] = $data['text'];
    $data['photo'] = $filenameToStore;
    $data['size'] = $filesize;
    unset($data['text']);
    unset($data['file']);
    $photo = Photo::create($data);
      
    if(!is_null($photo)) {
      return response()->json(["success" => true, "errors" => [], "message" => "photo created"]);
    }    
    else {
      return response()->json(["success" => false, "errors" => [], "message" => "photo not created"]);
    }
  }

  public function show($id) {
    $photo = Photo::find($id);
    
    if (!is_null($photo)) {
      return response()->json(["success" => true, "data" => $photo]);
    }
    else {
      return response()->json(["success" => false, "data" => []]);
    }
  }

  public function destroy($id) {
    $photo = Photo::find($id);
    $filepath = "photos/{$photo->album_id}/{$photo->photo}";

    if (Storage::disk('public')->exists($filepath)) {
      Storage::disk('public')->delete($filepath);
      $photo->delete();
      return response()->json(["success" => true, "errors" => [], "message" => "photo removed"]);
    }
    else {
      return response()->json(["success" => false, "errors" => [], "message" => "photo not removed"]);
    }
  }
}

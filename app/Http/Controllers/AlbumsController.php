<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Album;
use App\Photo;

class AlbumsController extends Controller
{
  private function getRows($data, $numPerRow) {
    $rows = [];

    while (count($data) > 0) {
      array_push($rows, array_splice($data, 0, $numPerRow));
    }

    return $rows;
  }

  public function index() {
    $albums = Album::all()->toArray();

    if (count($albums) > 0) {
      return response()->json(["success" => true, "data" => $this->getRows($albums, 3)]);
    }
    else {
      return response()->json(["success" => false, "data" => []]);
    }
  }

  public function store(Request $request) {
    $data = json_decode($request->album, true);
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
    $filenameWithExt = $data['file']->getClientOriginalName();
    $filename = pathinfo($filenameWithExt, PATHINFO_FILENAME);
    $extension = $data['file']->getClientOriginalExtension();
    $currentTime = time();
    $filenameToStore = "{$filename}_{$currentTime}.{$extension}";
    $path = $data['file']->storeAs('public/album_covers', $filenameToStore);

    $data['name'] = $data['text'];
    $data['cover_image'] = $filenameToStore;
    unset($data['text']);
    unset($data['file']);
    $album = Album::create($data);
      
    if(!is_null($album)) {            
      return response()->json(["success" => true, "errors" => [], "message" => "album created"]);
    }    
    else {
      return response()->json(["success" => false, "errors" => [], "message" => "album not created"]);
    }
  }

  public function show($id) {
    $album = Album::find($id);
    
    if (!is_null($album)) {
      $data = [
        'id' => $album->id,
        'name' => $album->name,
        'description' => $album->description,
        'cover_image' => $album->cover_image,
        'photos' => $this->getRows($album->photos->toArray(), 3)
      ];

      return response()->json(["success" => true, "data" => $data]);
    }
    else {
      return response()->json(["success" => false, "data" => []]);
    }
  }
}

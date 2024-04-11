<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Picture;
use App\Models\Plant;
use App\Models\Strain;

class UploadController extends Controller {

  public function pictures(Request $request) {
    if ($request->hasFile('pictures')) {
      $object = null;
      if ($request->plant_id) $object = Plant::findOrFail($request->plant_id);
      if ($request->strain_id) $object = Strain::findOrFail($request->strain_id);

      $files = $request->file('pictures');
      if (!is_array($files)) $files = [$files];

      foreach ($files as $file) if ($file->isValid()) $object->pictures()->attach(Picture::upload($file), ['default' => false]);

      return back()->with('success', 'Pictures uploaded successfully');
    } else {
      return back()->with('error', 'No picture selected.');
    }
  }
}

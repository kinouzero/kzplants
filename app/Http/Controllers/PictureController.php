<?php

namespace App\Http\Controllers;

use App\Models\Picture;

class PictureController extends Controller {

  public function src($id) {
    $picture = Picture::find($id);

    if ($picture) return response($picture->getContent())->header('Content-Type', sprintf('image/%s', $picture->ext()));
    else abort(404);
  }
}

<?php

namespace App\Http\Controllers;

use App\Events\PlantActionOccurred;
use App\Http\Requests\UploadPictureRequest;
use App\Models\Picture;
use App\Models\Plant;
use App\Models\Strain;
use Illuminate\Support\Facades\Log;

class UploadController extends Controller
{
    public function pictures(UploadPictureRequest $request)
    {

        if ($request->hasFile('pictures')) {
            $object = null;
            if ($request->plant_id) {
                $object = Plant::findOrFail($request->plant_id);
            }
            if ($request->strain_id) {
                $object = Strain::findOrFail($request->strain_id);
            }

            $files = $request->file('pictures');
            if (! is_array($files)) {
                $files = [$files];
            }

            foreach ($files as $file) {
                if ($file->isValid()) {
                    $picture = Picture::upload($file);
                    $object->pictures()->attach($picture, ['default' => false]);

                    Log::info('upload.picture', [
                        'user_id' => auth()->id(),
                        'plant_id' => $object instanceof Plant ? $object->id : null,
                        'strain_id' => $object instanceof Strain ? $object->id : null,
                        'picture_id' => $picture->id,
                        'mime' => $picture->mime,
                        'size' => $file->getSize(),
                    ]);

                    if ($object instanceof Plant) {
                        event(new PlantActionOccurred($object, auth()->user(), ['type' => 'picture_add', 'picture_id' => $picture->id]));
                    }
                }
            }

            return back()->with('success', __('ui.pictures_uploaded'));
        } else {
            return back()->with('error', 'No picture selected.');
        }
    }
}

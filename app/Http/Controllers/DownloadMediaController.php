<?php

namespace App\Http\Controllers;
use Spatie\MediaLibrary\Models\Media;

use Illuminate\Http\Request;

class DownloadMediaController extends Controller
{
   public function show($id)
   {
       //return $id;
       $mediaItem = Media::find($id);
       //return $mediaItem;
       return response()->download($mediaItem->getPath(), $mediaItem->file_name);
   }
}

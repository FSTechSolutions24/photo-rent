<?php

namespace App\Traits;

use Carbon\Carbon;
use App\Models\Media;
use App\Models\Photographer;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

trait HelperTrait
{
    public $foreign_key = 0;

    /**
     * Store dynamic table records
     */
    public function storeDynamicTableRecords($modelClass, $foreignKey, $foreignId, $items)
    {
        // Reset existing rows for this parent
        $modelClass::where($foreignKey, $foreignId)->delete();
        // dd($items);

        // Reinsert dynamically
        foreach ($items['rows'] as $item) {

            // Ensure foreign key is included
            $item[$foreignKey] = $foreignId;

            // Create new record (dynamic columns)
            $modelClass::create($item);
        }
    }

    public function unlink_media($mediaId, $galleryId)
    {
        // we need to check if this is my gallery or not from the media gallery_id check
        // delete media from storage
        // if successfully deleted then update storage then return true
        // else return false

        $photographer = Photographer::where('user_id', Auth::id())->first();

        if (! $photographer) {
            return false;
        }

        $media = Media::find($mediaId);

        if (!$media) {
            return false;
        }

        $size = $media->size;

        $mediaPath = $media->path;

        if (Storage::disk('public')->exists($mediaPath)) {
            Storage::disk('public')->delete($mediaPath);
        }

        $photographer->available_storage +=  $size;

        $photographer->save();

        return true;
    }

    public function convert_storage($size){
        if ($size >= 1073741824) { // 1 GB
            return number_format($size / 1073741824, 2) . ' GB';
        } elseif ($size >= 1048576) { // 1 MB
            return number_format($size / 1048576, 2) . ' MB';
        } elseif ($size >= 1024) { // 1 KB
            return number_format($size / 1024, 2) . ' KB';
        } else {
            return $size . ' bytes';
        }
    }
}

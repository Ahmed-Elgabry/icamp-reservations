<?php

namespace App\Observers;

use App\Models\Banner;
use Illuminate\Support\Facades\Storage;

class BannerObserver
{
    public function created(Banner $row)
    {

            
    }

    public function updating (Banner $row)
    {
        if (request()->has('image')) {
            if ($row->getRawOriginal('image') != 'default.png' && $row->getRawOriginal('image') != null){
                Storage::delete($row->getRawOriginal('image'));
            }
        }
    }

    /**
     * Handle the Banner "deleted" event.
     *
     * @param \App\Banner $row
     * @return void
     */
    public function deleted(Banner $row)
    {
        if ($row->getRawOriginal('image') != 'default.png' && $row->getRawOriginal('image') != null){

            Storage::delete($row->getRawOriginal('image'));
        }
    }

}

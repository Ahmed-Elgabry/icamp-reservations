<?php

namespace App\Observers;

use App\Models\Copy;
use Illuminate\Support\Facades\Storage;

class CopyObserver
{
    public function created(Copy $row)
    {

            
    }

    public function updating (Copy $row)
    {
        if (request()->has('image')) {
            if ($row->getRawOriginal('image') != 'default.png' && $row->getRawOriginal('image') != null){
                Storage::delete($row->getRawOriginal('image'));
            }
        }
    }

    /**
     * Handle the Copy "deleted" event.
     *
     * @param \App\Copy $row
     * @return void
     */
    public function deleted(Copy $row)
    {
        if ($row->getRawOriginal('image') != 'default.png' && $row->getRawOriginal('image') != null){

            Storage::delete($row->getRawOriginal('image'));
        }
    }

}

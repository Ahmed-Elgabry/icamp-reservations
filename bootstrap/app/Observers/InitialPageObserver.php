<?php

namespace App\Observers;

use App\Models\InitialPage;
use Illuminate\Support\Facades\Storage;

class InitialPageObserver
{
    public function created(InitialPage $row)
    {

            
    }

    public function updating (InitialPage $row)
    {
        if (request()->has('image')) {
            if ($row->getRawOriginal('image') != 'default.png' && $row->getRawOriginal('image') != null){
                Storage::delete($row->getRawOriginal('image'));
            }
        }
    }

    /**
     * Handle the InitialPage "deleted" event.
     *
     * @param \App\InitialPage $row
     * @return void
     */
    public function deleted(InitialPage $row)
    {
        if ($row->getRawOriginal('image') != 'default.png' && $row->getRawOriginal('image') != null){

            Storage::delete($row->getRawOriginal('image'));
        }
    }

}

<?php

namespace App\Observers;

use App\Models\City;
use Illuminate\Support\Facades\Storage;

class CityObserver
{
    public function created(City $row)
    {

            
    }

    public function updating (City $row)
    {
        if (request()->has('image')) {
            if ($row->getRawOriginal('image') != 'default.png' && $row->getRawOriginal('image') != null){
                Storage::delete($row->getRawOriginal('image'));
            }
        }
    }

    /**
     * Handle the City "deleted" event.
     *
     * @param \App\City $row
     * @return void
     */
    public function deleted(City $row)
    {
        if ($row->getRawOriginal('image') != 'default.png' && $row->getRawOriginal('image') != null){

            Storage::delete($row->getRawOriginal('image'));
        }
    }

}

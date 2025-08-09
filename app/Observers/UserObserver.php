<?php

namespace App\Observers;

use App\Models\User;
use Illuminate\Support\Facades\Storage;

class UserObserver
{
    public function created(User $row)
    {

            
    }

    public function updating (User $row)
    {
        if (request()->has('image')) {
            if ($row->getRawOriginal('image') != 'default.png' && $row->getRawOriginal('image') != null){
                Storage::delete($row->getRawOriginal('image'));
            }
        }
    }

    /**
     * Handle the User "deleted" event.
     *
     * @param \App\User $row
     * @return void
     */
    public function deleted(User $row)
    {
        if ($row->getRawOriginal('image') != 'default.png' && $row->getRawOriginal('image') != null){

            Storage::delete($row->getRawOriginal('image'));
        }
    }

}

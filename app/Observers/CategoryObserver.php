<?php

namespace App\Observers;

use App\Models\Category;
use Illuminate\Support\Facades\Storage;

class CategoryObserver
{
    public function created(Category $row)
    {

            
    }

    public function updating (Category $row)
    {
        if (request()->has('image')) {
            if ($row->getRawOriginal('image') != 'default.png'){
                Storage::delete($row->getRawOriginal('image'));
            }
        }
    }

    /**
     * Handle the Category "deleted" event.
     *
     * @param \App\Category $row
     * @return void
     */
    public function deleted(Category $row)
    {
        if ($row->getRawOriginal('image') != 'default.png'){

            Storage::delete($row->getRawOriginal('image'));
        }
    }

}

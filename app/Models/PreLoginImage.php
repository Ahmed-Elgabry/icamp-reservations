<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\UploadTrait;

class PreLoginImage extends Model
{
    use HasFactory , UploadTrait;

    protected $guarded = [];

    public function setImageAttribute($value)
    {
        if($value){
          return $this->attributes['image'] = $this->StoreFile('logins' , $value);
        }
    }

    public function getImageAttribute($value)
    {
        if($value)
        {
            return asset('storage/'.$value);
        }
    }
}

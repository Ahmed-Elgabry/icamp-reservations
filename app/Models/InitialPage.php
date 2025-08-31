<?php

namespace App\Models;

use App\Traits\UploadTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class InitialPage extends Model
{
    use HasFactory , HasTranslations , UploadTrait;
 
    public $translatable = ['desc'];

    protected $fillable = ['image' , 'desc' , 'order' , 'is_active'];


    protected function asJson($value)
    {
        return json_encode($value, JSON_UNESCAPED_UNICODE);
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', 1);
    }

    
    public function setImageAttribute($value)
    {
        if($value){
          return $this->attributes['image'] = $this->StoreFile('initalpages' , $value);
        }
    }
}

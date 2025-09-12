<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BaseModel extends Model
{
    use HasFactory;

    public function scopeSearch($query, $searchArray = [])
    {
        $query->where(function ($query) use ($searchArray) {
            if ($searchArray) {
                foreach ($searchArray as $key => $value) {
                    if (str_contains($key, '_id')) { 
                        if ($value != null) {
                            $query->Where($key , $value);
                        }
                    }elseif ($key == 'order' ) {
                    }elseif ($key == 'created_at_min' ) { 
                        if ($value != null ) {
                            $query->WhereDate('created_at' , '>=' , $value);
                        }
                    }elseif ($key == 'created_at_max') { 
                        if ($value != null ) {
                            $query->WhereDate('created_at' , '<=' , $value);
                        }
                    }else{
                        if ($value != null ) {
                            $query->Where($key, 'like', '%'.$value.'%');
                        }
                    }
                }
            }
        });
        return $query->orderBy('created_at' , request()->searchArray && request()->searchArray['order'] ? request()->searchArray['order'] : 'DESC' );
    }


    protected function asJson($value)
    {
        return json_encode($value, JSON_UNESCAPED_UNICODE);
    }

}

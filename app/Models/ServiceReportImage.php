<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ServiceReportImage extends Model
{
    protected $guarded = [];

    public function service()
    {
        return $this->belongsTo(ServiceReport::class);
    }
}

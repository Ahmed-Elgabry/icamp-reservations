<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ServiceSiteAndCustomerService extends Model
{
    use HasFactory;
    protected $fillable = [
        'service_site',
        'workername_ar',
        'workername_en',
        'workerphone',
    ];
}

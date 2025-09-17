<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ServiceSiteAndCustomerService extends Model
{
    use HasFactory;

    protected $table = 'service_site_customer_services';

    protected $fillable = [
        'serviceSite',
        'workername_en',
        'workername_ar',
        'workerphone',
    ];
}

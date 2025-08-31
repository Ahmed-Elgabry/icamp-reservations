<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TermsSittng extends Model
{
    use HasFactory;
    protected $table = 'terms_sittngs';
    protected $fillable = [
        'id',
        'logo',
        'description',
        'terms',
        'commercial_license_ar',
        'commercial_license_en',
        'company_name',
        'order_id',
        'user_id',
        'created_at',
        'updated_at',
    ];
    public function order()
    {
        return $this->belongsTo(Order::class);
    }
}

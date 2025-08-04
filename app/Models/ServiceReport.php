<?php

namespace App\Models;

use App\Traits\UploadTrait;
use Illuminate\Support\Facades\Log;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ServiceReport extends Model
{
    use HasFactory, UploadTrait;
    protected $guarded = [];
    public function setImageAttribute($value)
    {
        if ($value && is_file($value)) {
            $this->attributes['image'] = $this->StoreFile('reports', $value);
        }
    }

    public function getImageAttribute($value)
    {
        return $value ? asset('storage/' . $value) : null;
    }

    // دالة لحفظ الملفات
    protected function StoreFile($directory, $file)
    {
        try {
            return $file->store($directory, 'public');
        } catch (\Exception $e) {
            \Log::error('File storage error: ' . $e->getMessage());
            return null;
        }
    }

    public function images()
    {
        return $this->hasMany(ServiceReportImage::class , 'service_report_id');
    }

    public function latestImage()
    {
        return $this->hasOne(ServiceReportImage::class)->latestOfMany();
    }

}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LogActivity extends Model
{
    use HasFactory;

    protected $fillable = ['subject', 'url', 'method', 'ip', 'agent','admin_id'];
    
    public function admin() {
    return $this->belongsTo(admin::class, 'admin_id', 'id');
    }
    
}

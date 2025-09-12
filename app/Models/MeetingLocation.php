<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MeetingLocation extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'address',
        'is_active'
    ];
}

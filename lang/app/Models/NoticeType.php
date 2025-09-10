<?php

// app/Models/NoticeType.php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class NoticeType extends Model
{
    protected $fillable = ['name', 'is_active'];
}

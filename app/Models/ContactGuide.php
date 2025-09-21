<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ContactGuide extends Model
{
    use HasFactory;

    protected $fillable = [
        'entity_name',
        'contact_person_name',
        'primary_phone',
        'secondary_phone',
        'fixed_phone',
        'email',
        'photo',
        'notes',
    ];
}

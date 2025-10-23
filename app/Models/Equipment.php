<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Equipment extends Model
{
    protected $fillable = [
        'equipment_name',
        'brand',
        'condition',
        'image_url',
        'description',
    ];
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    use HasFactory;

    // Les champs qui peuvent être assignés en masse
    protected $fillable = [
        'title',
        'start',
        'end',
        'all_day',
        'color',
        'description',
    ];

    // Si tu veux que start et end soient traités comme des dates
    protected $dates = [
        'start',
        'end',
    ];
}
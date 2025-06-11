<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pet extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'name',
        'species',
        'breed',
        'dob',
        'gender',
        'weight',
        'weight_unit',
        'allergies',
        'health_notes',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}

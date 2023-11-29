<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Coffee extends Model
{
   protected $fillable = [
        'imagePath',
        'title',
        'sizes', 
    ];

    // Accessor for 'sizes' attribute
    protected $casts = [
        'sizes' => 'array',
    ];
    public function getSizesAttribute($value)
    {
        return json_decode($value, true);
    }

    // Mutator for 'sizes' attribute
    public function setSizesAttribute($value)
    {
        $this->attributes['sizes'] = json_encode($value);
    }
}

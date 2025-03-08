<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LoginAttempt extends Model
{
    use HasFactory;

    protected $table = 'login_attempts';

    
    protected $fillable = [
        'user_id',
        'ip_address',
        'user_agent',
        'status',
    ];

   
    protected $casts = [
        'user_id' => 'integer',
        'status' => 'string',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}


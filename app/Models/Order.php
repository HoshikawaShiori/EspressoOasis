<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
   protected $fillable = [
      'name', 'address', 'email', 'phone', 'moreInfo','orderStatus',
  ];
   public function user() {
    return  $this->belongsTo(User::class) ;
   }
}

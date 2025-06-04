<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserPhoto extends Model
{
    protected $fillable = ['user_id', 'photo_path', 'is_profile'];
    public function photos()
    {

     return $this->belongsTo(User::class);
    }
}

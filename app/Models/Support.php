<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Support extends Model
{
    use HasFactory;

        
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function meta()
    {
        return $this->hasMany(Supportmeta::class);
    }
}

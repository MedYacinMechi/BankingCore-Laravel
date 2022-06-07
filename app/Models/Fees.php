<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Fees extends Model
{
    use HasFactory;

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function transaction()
    {
        if ($this->type != 0) { return null; }
        return Transaction::where("id", $this->ref_id)->first();
    }
}

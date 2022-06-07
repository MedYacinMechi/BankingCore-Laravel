<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Hold extends Model
{
    use HasFactory;

    public function account()
    {
        return $this->belongsTo(Account::class, 'acc_id');
    }

    public function inHoldPeriod()
    {
        return ! is_null($this->lockuntil);
    }
}
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AccountType extends Model
{
    use HasFactory;

    public function currency()
    {
        return $this->belongsTo(Currency::class, 'currencyid');
    }

    public function id_represent()
    {
        return $this->id;
    }

    public function name_represent()
    {
        return $this->name;
    }
}

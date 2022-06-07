<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TransactionFees extends Model
{
    use HasFactory;

    public function currency() {
        #FOLD
        return $this->belongsTo(Currency::class, 'currencyid', 'id');
    }

    public function application() {
        #FOLD
        if ($this->type == 0) { return __("Same User (Same Currency)"); }
        if ($this->type == 1) { return __("Same User (Different Currency)"); }
        if ($this->type == 2) { return __("Cross Users (Same Currency)"); }
        if ($this->type == 3) { return __("Cross Users (Different Currency)"); }
        if ($this->type == 4) { return __("Wire (Same Currency)"); }
        if ($this->type == 5) { return __("Wire (Different Currency)"); }
        return __("Unknown");
    }
    
}

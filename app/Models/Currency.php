<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Currency extends Model
{
    use HasFactory;

    public $timestamps = false;

    public function getsymbol() {
        if (empty($this->symbol)) { return $this->ISOcode; }
        else { return $this->symbol; }
    }
}

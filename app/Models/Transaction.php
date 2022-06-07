<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;
    
    public function user() {
        #FOLD
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function destinationuser() {
        #FOLD
        return $this->belongsTo(User::class, 'destuserid', 'id');
    }

    public function destinationtx() {
        #FOLD
        return Transaction::where("originid", $this->id)->first();
    }

    public function origintx() {
        #FOLD
        return $this->belongsTo(Transaction::class, 'originid', 'id');
    }

    public function wire() {
        #FOLD
        return $this->belongsTo(WireData::class, 'wireid', 'id');
    }

    public function feevalue() {
        $fee = Fees::where("refid", $this->id)->where("type", 0)->first();
        return $fee == null ? 0 : $fee->value;
    }

    public function crosscurrency() {
        #todo: for now wires are always unicurrency
        if ($this->wireid != null) { return false; }
        if ($this->origintx == null) {
            return $this->currency()->id != $this->destinationtx()->currency()->id;
        } else {
            return $this->currency()->id != $this->origintx->currency()->id;
        }
    }

    public function currencycode() {
        $currencyid = Account::where("id", $this->acc_id)->first()->currencyid;
        $currency   = Currency::where("id", $currencyid)->first();
        return empty($currency->symbol) ? $currency->ISOcode : $currency->symbol;
    }

    public function currency() {
        $currencyid = Account::where("id", $this->acc_id)->first()->currencyid;
        return Currency::where("id", $currencyid)->first();
    }

    public function account() {
        #FOLD
        return $this->belongsTo(Account::class, 'acc_id', 'id');
    }
}

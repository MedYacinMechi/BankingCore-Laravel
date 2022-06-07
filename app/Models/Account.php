<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Account extends Model
{
    use HasFactory;

    # <MBI>

    public function user() {
        #FOLD
        return $this->belongsTo(User::class, 'user_id');
    }

    public function acctype() {
        #FOLD
        return $this->belongsTo(AccountType::class, 'type');
    }

    public function currency() {
        #FOLD
        return $this->belongsTo(Currency::class, 'currencyid');
    }

    public function lastactivity() {
        $transaction = Transaction::where("acc_id", $this->id)->orderByDesc("created_at")->first();
        return $transaction != null ? $transaction->created_at->diffForHumans() : "Never";
    }

    # available (spendable) balance
    public function balance() {
        # pending and executed debit transactions
        $debits  = Transaction::where("acc_id", $this->id)
                              ->where('type', 0)
                              ->where('status', '!=', 2)
                              ->sum('value');

        # executed credit transactions
        $credits = Transaction::where("acc_id", $this->id)
                              ->where('type', 1)
                              ->where('status', 1)
                              ->sum('value');
        # pending and executed fees
        $fees    = Fees::where("acc_id", $this->id)
                       ->where("type", 0)
                       ->where('status', '!=', 2)
                       ->sum('value');
        return $credits - ($debits + $fees);
    }

    # available (spendable) balance at specific point of time
    public function balance_at($dtlimit) {
        # pending and executed debit transactions
        $debits  = Transaction::where("acc_id", $this->id)
                              ->where('type', 0)
                              ->where('status', '!=', 2)->where('created_at', '<=', $dtlimit)
                              ->sum('value');

        # executed credit transactions
        $credits = Transaction::where("acc_id", $this->id)
                              ->where('type', 1)
                              ->where('status', 1)->where('created_at', '<=', $dtlimit)
                              ->sum('value');
        # pending and executed fees
        $fees    = Fees::where("acc_id", $this->id)
                       ->where("type", 0)
                       ->where('status', '!=', 2)->where('created_at', '<=', $dtlimit)
                       ->sum('value');
        return $credits - ($debits + $fees);
    }

    # all balance including pendings
    public function currentbalance() {
        # executed debit transactions
        $debits  = Transaction::where("acc_id", $this->id)
                              ->where('type', 0)
                              ->where('status', 1)
                              ->sum('value');

        # executed credit transactions
        $credits = Transaction::where("acc_id", $this->id)
                              ->where('type', 1)
                              ->where('status', 1)
                              ->sum('value');
        # pending and executed fees
        $fees    = Fees::where("acc_id", $this->id)
                       ->where("type", 0)
                       ->where('status', 1)
                       ->sum('value');
        return $credits - ($debits + $fees);
    }

    # all balance including pendings at specific point of time
    public function currentbalance_at($dtlimit) {
        # executed debit transactions
        $debits  = Transaction::where("acc_id", $this->id)
                              ->where('type', 0)
                              ->where('status', 1)->where('created_at', '<=', $dtlimit)
                              ->sum('value');

        # executed credit transactions
        $credits = Transaction::where("acc_id", $this->id)
                              ->where('type', 1)
                              ->where('status', 1)->where('created_at', '<=', $dtlimit)
                              ->sum('value');
        # pending and executed fees
        $fees    = Fees::where("acc_id", $this->id)
                       ->where("type", 0)
                       ->where('status', 1)->where('created_at', '<=', $dtlimit)
                       ->sum('value');
        return $credits - ($debits + $fees);
    }

    public function name_represent() {
        if ($this->user->type == 0) {
            return $this->user->firstname." ".$this->user->lastname;
        }
        else { return $this->user->firstname." ".$this->user->lastname." (".$this->user->companyname.")"; }
    }

    # </MBI>
}

<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\Account;
use App\Models\Transaction;
use App\Models\User;
use App\Models\Currency;
use App\Models\TransactionFees;
use Illuminate\Support\Facades\Storage;

class TransferController extends Controller
{
    /* <MBI> */

    private function getrelations() {
        $transactions = Transaction::where("user_id", Auth::user()->id)->where("status", '!=',  2)->get();
        $relations    = [];
        $duplicates   = [];
        $i = 0;
        foreach ($transactions as $tx) {
            if ($tx->info != null) { continue; }
            if ($tx->type == 0) {
                $dest = Transaction::where("originid", $tx->id)->first();
                if ($dest == null) { continue; }
                if ($dest->user_id == Auth::user()->id) { continue; }
                $user = User::where("id", $dest->user_id)->first();
                $acc  = Account::where("id", $dest->acc_id)->first();
                if ($user == null or $acc == null) { continue; }
                if (in_array($acc->identifier, $duplicates)) { continue; }
                $cur  = Currency::where("id", $acc->currencyid)->first();
                if ($cur == null) { continue; }
                array_push($duplicates, $acc->identifier);
                array_push($relations, [$user->firstname." ".$user->lastname, $acc->identifier, $cur->ISOcode]);
                $i++;
            } else {
                $origin = Transaction::where("originid", $tx->id)->first();
                if ($origin == null) { continue; }
                if ($origin->user_id == Auth::user()->id) { continue; }
                $user = User::where("id", $origin->user_id)->first();
                $acc  = Account::where("id", $origin->acc_id)->first();
                if ($user == null or $acc == null) { continue; }
                if (in_array($acc->identifier, $duplicates)) { continue; }
                $cur  = Currency::where("id", $acc->currencyid)->first();
                if ($cur == null) { continue; }
                array_push($duplicates, $acc->identifier);
                array_push($relations, [$user->firstname." ".$user->lastname, $acc->identifier, $cur->ISOcode]);
                $i++;
            } if ($i > 5) { break; }
        }
        return $relations;
    }

    public function internal($viewname) {
        $accounts  = Account::where("user_id", Auth::user()->id)->where("status", 1)->get();
        $exhscheme = Storage::disk('public')->get('currency_rates.json');
        $exhscheme = json_decode($exhscheme, true);
        $feestruct = TransactionFees::get();
        $useraccs  = [];
        $fees      = [];
        foreach ($accounts  as $acc) { array_push($useraccs, $acc->identifier); }
        foreach ($feestruct as $fee) {
            $code = Currency::where("id", $fee->currencyid)->first()["ISOcode"];
            array_push($fees, [$code, $fee->fixed, $fee->percent, $fee->type]);
        }
        return view("user.transfer.".$viewname, compact("accounts", "exhscheme", "fees", "useraccs"));
    }

    public function multiple_users() { 
        #FOLD
        return $this->internal("multipleusers");
    }

    public function between_accounts() { 
        #FOLD
        return $this->internal("betweenaccounts");
    }

    public function other_user() { 
        #FOLD
        return $this->internal("anotheruser");
    }

    public function wire() {
        $accounts   = Account::where("user_id", Auth::user()->id)->where("status", 1)->get();
        $currencies = Currency::where("status", 1)->where("wireable", 1)->get();
        return view('user.transfer.wire', compact('accounts', 'currencies'));
    }

    /* </MBI> */
}
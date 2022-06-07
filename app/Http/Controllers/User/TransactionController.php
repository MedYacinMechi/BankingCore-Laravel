<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use App\Models\TransactionFees;
use App\Models\Fees;
use App\Models\WireData;
use App\Models\Account;
use App\Models\Hold;
use App\Models\Currency;
use App\Models\User;
use App\Models\SystemRequest;
use Barryvdh\DomPDF\Facade as PDF;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Notifications\Usertransctionnotification;

class TransactionController extends Controller
{
    /* <MBI> */

    #todo: move to Lib\Common
    public function jsonresp($error) {
        #FOLD
        return json_encode(array("error" => $error));
    }

    public function multipleTransfer(Request $request) {
        if (Auth::user()->status != 1) { return redirect()->back()->with("error", "Not authorized to send transactions"); }
        if (Auth::user()->status != 1) { return redirect()->back()->with("error", "Not authorized to send transactions"); }
        if (!$request->hasFile("csvdata") || !$request->file("csvdata")->isValid()) { return redirect()->back()->with("error", "Invalid CSV file"); }
        $filename = "csvdata.".$request->file("csvdata")->extension();
        $path     = Storage::putFileAs('multiple-transfers/'.Auth::user()->id, $request->file("csvdata"), $filename);
        $file     = fopen(storage_path("/app/".$path), "r");
        $errors   = [];
        $nline    = 1;
        $succeded = 0;
        while (($data = fgetcsv($file, 1000, ",")) !== FALSE) {
            if (count($data) != 3) { array_push($errors, "[Line:$nline] Invalid CSV format"); $nline++; continue; }
            $accn   = $data[0];
            $amount = $data[1];
            $msg    = $data[2];
            $valid  = is_numeric($amount);
            if (!$valid) { array_push($errors, "[Line:$nline] Invalid Amount"); $nline++; continue; }
            if ($amount <= 0) { array_push($errors, "[Line:$nline] Amount not Allowed"); $nline++; continue; }
            $accountc = Account::where("identifier", $accn)->first();
            if ($accountc == null) { array_push($errors, "[Line:$nline] Payee account does not exist"); $nline++; continue; }
            if ($accountc->status != 1) { array_push($errors, "[Line:$nline] Payee account not authorized to receive transactions"); $nline++; continue; }
            if ($accountc->user->status != 1) { array_push($errors, "[Line:$nline] Payee not authorized to receive transactions"); $nline++; continue; }
            $accountd = Account::where("identifier", $request->accountnum)->where("user_id", Auth::user()->id)->first();
            if ($accountd == null) { array_push($errors, "[Line:$nline] Debit account does not exist"); $nline++; continue; }
            if ($accountd->status != 1) { array_push($errors, "[Line:$nline] Debit account not authorized to send transactions"); $nline++; continue; }
            if ($accountd->id == $accountc->id) { array_push($errors, "[Line:$nline] Debit and Credit account cannot be the same"); $nline++; continue; }
            $valid = $accountd->balance() >= $amount;
            if (!$valid) { array_push($errors, "[Line:$nline] Insufficient balance"); $nline++; continue; }
            $result = $this->structTransfer($accountd, $accountc, $amount, $request->txkey, $request->msgref);
            if ($result !== true) { array_push($errors, "[Line:$nline] $result"); $nline++; continue; }
            $nline++;
            $succeded++;
        }
        if ($succeded > 0) { return redirect()->back()->with('success', "Sent ($succeded) ".($succeded == 1 ? "Transaction" : "Transactions"))->with("errors", $errors); }
        else { return redirect()->back()->with("errors", $errors); }
    }

    public function creditInternal(Request $request) {
        if (Auth::user()->status != 1) { return redirect()->back()->with("error", "Not authorized to send transactions"); }
        if ($request->source != 0 && $request->source != 1) { return redirect()->back()->with("error", "400 Bad Request"); }
        $valid = $request->payee_accountnum != null;
        if (!$valid && $request->source == 1) { return redirect()->back()->with("error", "Required payee account number"); }
        $valid = $request->payee_accountnum_select != null;
        if (!$valid && $request->source == 0) { return redirect()->back()->with("error", "Required payee account number"); }
        $valid = is_numeric($request->amount);
        if (!$valid) { return redirect()->back()->with("error", "Invalid Amount"); }
        if ($request->amount <= 0) { return redirect()->back()->with("error", "Amount not allowed"); }
        $payee_accn = $request->source == 0 ? $request->payee_accountnum_select : $request->payee_accountnum;
        $accountc   = Account::where("identifier", $payee_accn)->first();
        $accountc_hold = Hold::where("acc_id", $accountc->id)->first();
        $accountc_isLocked = false;
        if (!is_null($accountc_hold)) { 
            if(!is_null($accountc_hold->lockuntil)){$accountc_isLocked = true;}
        }
        if ($accountc == null) { return redirect()->back()->with("error", "Payee account does not exist"); }
        if ($accountc->status != 1) { return redirect()->back()->with("error", "Payee account not authorized to receive transactions"); }
        if ($accountc_isLocked) { return redirect()->back()->with("error", "Payee account is in lock period"); }
        if ($accountc->user->status != 1) { return redirect()->back()->with("error", "Payee not authorized to receive transactions"); }
        $accountd = Account::where("identifier", $request->accountnum)->where("user_id", Auth::user()->id)->first();
        $accountd_hold = Hold::where("acc_id", $accountd->id)->first();
        $accountd_isLocked = false;
        if (!is_null($accountd_hold)) { 
            if(!is_null($accountd_hold->lockuntil)){$accountd_isLocked = true;}
        }
        if ($accountd == null) { return redirect()->back()->with("error", "Debit account does not exist"); }
        if ($accountd->status != 1) { return redirect()->back()->with("error", "Debit account not authorized to send transactions"); }
        if ($accountd_isLocked) { return redirect()->back()->with("error", "Debit account is in lock period"); }
        if ($accountd->id == $accountc->id) { return redirect()->back()->with("error", "Debit and Credit account cannot be the same"); }
        $valid = $accountd->balance() >= $request->amount;
        if (!$valid) { return redirect()->back()->with("error", "Insufficient balance"); }
        $result = $this->structTransfer($accountd, $accountc, $request->amount, $request->txkey, $request->msgref);
        if ($result !== true) { return redirect()->back()->with("error", $result); }
        return redirect()->back()->with('success', "Transfer Submitted Successfully");
    }

    public function structTransfer($accountd, $accountc, $amount, $txkey, $msgref) {
        $sameuser    = $accountd->user_id    == $accountc->user_id;
        $unicurrency = $accountd->currencyid == $accountc->currencyid;
        $rates   = Storage::disk('public')->get('currency_rates.json');
        $rates   = json_decode($rates, true);
        $feeinfo = null;
        if ($sameuser && $unicurrency) {
            $feeinfo  = $this->applyfee(0, $accountd->currencyid, $amount);
            if ($feeinfo["error"] != null) { return $feeinfo["error"]; }
            $this->transact($accountd, $accountc, $feeinfo, $txkey, $msgref, null);
        } else if ($sameuser && !$unicurrency) { 
            $feeinfo  = $this->applyfee(1, $accountd->currencyid, $amount);
            if ($feeinfo["error"] != null) { return $feeinfo["error"]; }
            $rate     = $rates[$accountd->currency->ISOcode][$accountc->currency->ISOcode];
            $postrate = $feeinfo["amount"] * $rate;
            $this->transact($accountd, $accountc, $feeinfo, $txkey, $msgref, $postrate);
        } else if (!$sameuser && $unicurrency) { 
            $feeinfo  = $this->applyfee(2, $accountd->currencyid, $amount);
            if ($feeinfo["error"] != null) { return $feeinfo["error"]; }
            $this->transact($accountd, $accountc, $feeinfo, $txkey, $msgref, null);
        } else if (!$sameuser && !$unicurrency) { 
            $feeinfo  = $this->applyfee(3, $accountd->currencyid, $amount);
            if ($feeinfo["error"] != null) { return $feeinfo["error"]; }
            $rate     = $rates[$accountd->currency->ISOcode][$accountc->currency->ISOcode];
            $postrate = $feeinfo["amount"] * $rate;
            $this->transact($accountd, $accountc, $feeinfo, $txkey, $msgref, $postrate);
        }
        return true;
    }

    public function transact($accountd, $accountc, $feeinfo, $txkey, $msgref, $postrate) {
        $dnameref = $accountd->user->username." ".$accountd->user->firstname." ".$accountd->user->lastname;
        $cnameref = $accountc->user->username." ".$accountc->user->firstname." ".$accountc->user->lastname;
        $sameuser = $accountd->user_id == $accountc->user_id;
        $now      = date('Y-m-d H:i:s');
        $tdebit               = new Transaction();
        $tcredit              = new Transaction();
        $fee                  = new Fees();
        $sysreq               = new SystemRequest();
        $tdebit->value        = $feeinfo["amount"];
        $tdebit->type         = 0;
        $tdebit->currencyid   = $accountd->currencyid;
        $tdebit->acc_id       = $accountd->id;
        $tdebit->user_id      = $accountd->user_id;
        $tdebit->destuserid   = $accountc->user_id;
        $tdebit->txkey        = $txkey;
        $tdebit->nameref      = $dnameref;
        $tdebit->usernote     = $msgref;
        $tdebit->status       = 1;
        $tdebit->created_at   = $now;
        $tdebit->save();
        $tcredit->value       = $postrate == null ? $feeinfo["amount"] : $postrate;
        $tcredit->type        = 1;
        $tcredit->originid    = $tdebit->id;
        $tcredit->currencyid  = $accountc->currencyid;
        $tcredit->acc_id      = $accountc->id;
        $tcredit->user_id     = $accountc->user_id;
        $tcredit->txkey       = $txkey;
        $tcredit->nameref     = $cnameref;
        $tcredit->usernote    = $msgref;
        $tcredit->status      = $txkey == null ? 1 : 0;
        $tcredit->created_at  = $now;
        $tcredit->save();
        $fee->value           = $feeinfo["rawfee"];
        $fee->type            = 0;
        $fee->status          = 1;
        $fee->refid           = $tdebit->id;
        $fee->ref_feestruct   = $feeinfo["id"];
        $fee->acc_id          = $accountd->id;
        $fee->user_id         = $accountd->user_id;
        $fee->nameref         = $dnameref;
        $fee->created_at      = $now;
        $fee->save();
        $sysreq->user_id      = Auth::id();
        $sysreq->subject      = $sameuser ? "Transfer Between Accounts" : "Cross User Transfer";
        $sysreq->type         = 0;
        $sysreq->ref_id       = $tdebit->id;
        $sysreq->importance   = $sameuser ? 0 : 1;
        $sysreq->status       = 1;
        $sysreq->created_at   = $now;
        $sysreq->save();
       /* $user=User::find($accountd->user_id);
            $post=[
                'type'=>' new trasanction ',
                'message'=>'you have a new  transaction add to your transaction list'
                    ];
              $user->notify(new Usertransctionnotification($post));*/
        return true;
    }

    public function applyfee($type, $currencyid, $amount) {
        $fee = $this->getfeestruct($type, $currencyid);
        if ($fee["error"] != null) { return array("error" => $fee["error"]); }
        $fee = $fee["struct"];
        if ($fee->fixed != null) { $postfee = $amount - $fee->fixed; }
        else { $postfee = $amount - (($fee->percent / 100) * $amount); }
        if ($postfee <= 0) { return array("error" => "Invalid amount after fee deduction"); }
        return array("error" => null, 
            "id"     => $fee->id,
            "amount" => $postfee, 
            "rawfee" => $fee->percent != null ? (($fee->percent / 100) * $amount) : $fee->fixed);
    }

    public function getfeestruct($type, $currencyid) {
        $fee = TransactionFees::where("currencyid", $currencyid)->where("type", $type)->first();
        if ($fee == null) { return array("error" => $this->jsonresp("Fee structure not defined")); }
        if ($fee->fixed == null && $fee->percent == null) { return array("error" => $this->jsonresp("Fee must be either fixed/percent")); }
        return array("error" => null, "struct" => $fee);
    }

    public function createwire(Request $request) {
        # validation & prep
        if (Auth::user()->status != 1) { return redirect()->back()->with("error", "Not authorized to send transactions"); }
        if (!is_numeric($request->amount)) { return redirect()->back()->with('error', 'Invalid Amount'); }
        $rates = Storage::disk('public')->get('currency_rates.json');
        $rates = json_decode($rates, true);
        $now   = date('Y-m-d H:i:s');
        $cur   = Currency::where("id", $request->currency)->where("status", 1)->where("wireable", 1)->first();
        if ($cur == null) { return redirect()->back()->with('error', 'Invalid Currency'); }
        $accountd = Account::where("id", $request->accid)->where("user_id", Auth::user()->id)->first();
        if ($accountd == null) { return redirect()->back()->with('error', 'Invalid Debit Account'); }
        if ($accountd->status != 1) { return redirect()->back()->with("error", "Debit account not authorized to send transactions"); }
        $valid    = $accountd->balance() >= $request->amount;
        if (!$valid) { return redirect()->back()->with('error', "Insufficient balance"); }
        $dnameref = $accountd->user->username." ".$accountd->user->firstname." ".$accountd->user->lastname;
        $samecurrency = $accountd->currencyid == $cur->id;
        $feeinfo  = $this->applyfee($samecurrency ? 4 : 5, $cur->id, $request->amount);
        if ($feeinfo["error"] != null) { return redirect()->back()->with('error', $feeinfo["error"]); }
        $rate     = $rates[$accountd->currency->ISOcode][$cur->ISOcode];
        $postrate = $feeinfo["amount"] * $rate;
        # push to system
        $wire                 = new Wiredata();
        $wire->_56a_swift_bic = $request->f56a_swift_bic;
        $wire->_56a_name      = $request->f56a_name;
        $wire->_56a_address   = $request->f56a_address;
        $wire->_56a_city      = $request->f56a_city;
        $wire->_56a_country   = $request->f56a_country;
        $wire->_56a_ncs       = $request->f56a_ncs;
        $wire->_56a_aba_rtn   = $request->f56a_aba_rtn;
        $wire->_56a_iban      = $request->f56a_iban;
        $wire->_57a_swift_bic = $request->f57a_swift_bic;
        $wire->_57a_name      = $request->f57a_name;
        $wire->_57a_address   = $request->f57a_address;
        $wire->_57a_city      = $request->f57a_city;
        $wire->_57a_country   = $request->f57a_country;
        $wire->_57a_ncs       = $request->f57a_ncs;
        $wire->_57a_aba_rtn   = $request->f57a_aba_rtn;
        $wire->_59_name       = $request->f59_name;
        $wire->_59_address    = $request->f59_address;
        $wire->_59_iban       = $request->f59_iban;
        $wire->_70_refmessage = $request->f70_msgref;
        $wire->status         = 0;
        $wire->type           = 0;
        $wire->created_at     = $now;
        $t                    = new Transaction();
        $wire->_32a_currency  = $cur->ISOcode;
        $wire->_32a_value     = $samecurrency == null ? $feeinfo["amount"] : $postrate;
        $t->value             = $feeinfo["amount"];
        $t->currencyid        = $accountd->currencyid;
        $t->acc_id            = $accountd->id;
        $t->user_id           = $accountd->user_id;
        $t->nameref           = $dnameref;
        $t->type              = 0;
        $t->status            = 0;
        $t->created_at        = $now;
        $wire->save();
        $t->wireid            = $wire->id;
        $t->save();
        $fee                  = new Fees();
        $fee->value           = $feeinfo["rawfee"];
        $fee->type            = 0;
        $fee->status          = 0;
        $fee->refid           = $t->id;
        $fee->ref_feestruct   = $feeinfo["id"];
        $fee->acc_id          = $accountd->id;
        $fee->user_id         = $accountd->user_id;
        $fee->nameref         = $dnameref;
        $fee->created_at      = $now;
        $fee->save();
        $sysreq               = new SystemRequest();
        $sysreq->user_id      = Auth::id();
        $sysreq->subject      = "Outgoing Wire Transfer";
        $sysreq->type         = 0;
        $sysreq->ref_id       = $t->id;
        $sysreq->importance   = 2;
        $sysreq->status       = 0;
        $sysreq->created_at   = $now;
        $sysreq->save();
        /*$user=User::find($accountd->user_id);
            $post=[
                'type'=>' wire trasanction ',
                'message'=>'your wire  transaction : Outgoing Wire Transfer is transmitted'
                    ];
              $user->notify(new Usertransctionnotification($post));*/
        return redirect()->back()->with('success', "Wire Submitted Successfully");
    }

    public function accountISOcurrency(Request $request) {
        $acc = Account::where("identifier", $request->identifier)->first();
        if ($acc == null) { return json_encode(array("error" => "Account does not exist")); }
        if ($acc->currency == null) { return json_encode(array("error" => "Account currency does not exist")); }
        $curISO = $acc->currency->ISOcode;
        return json_encode(array(
            "error"   => null,
            "ISOcode" => $curISO
        ));
    }

    public function history() {
        $transactions = Transaction::where('user_id', Auth::id())
            ->doesntHave('origintx')
            ->orWhereHas('origintx', function ($t) { $t->where('user_id', '!=', Auth::id()); })
            ->where('user_id', Auth::id())->orderByDesc("created_at")->paginate(25);
        return view('user.transaction.history', compact('transactions'));
    }

    /* </MBI> */

    

    // Transaction Search
    public function transcctionSearch(Request $request)
    {
        $start_date       = $request->start_date . " 00:00:00";
        $end_date         = $request->end_date . " 23:59:59";
        $search_statement = Transaction::where('user_id', Auth::id())->whereBetween('created_at', [$start_date, $end_date])->get();
        return view('user.transaction.transaction_search', compact('search_statement', 'start_date', 'end_date'));
    }

    public function view($id){
        $transaction = Transaction::where('user_id', Auth::id())->findOrFail($id);
        return view('user.transaction.view', compact('transaction'));
    }

    public function transactionPDF(){
        $transactions = Transaction::where('user_id', Auth::id())->latest()->get();
        $now = Carbon::now();
        $now = $now->toDateTimeString();
        $file_name = 'otherbank_report_' . $now. '.pdf';
        $pdf = PDF::loadView('user.transaction.pdf', compact('transactions'));
        return $pdf->download($file_name);
    }
}

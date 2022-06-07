<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Deposit;
use App\Models\Transaction;
use App\Models\Fees;
use App\Models\Account;
use App\Models\Currency;
use App\Models\Withdraw;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

class DashboardController extends Controller
{
    private function sumtype($transactions, $type, $account, $ISOcode, $curjar) {
        foreach ($transactions as $t) {
            if ($type == 0) { if ($t->destuserid != null) { if ($t->destuserid == $account->user_id) { continue; }}}
            else { if ($t->origintx != null) { if ($t->origintx->user_id == $account->user_id) { continue; }}}
            if ($type == 0) { $fee = Fees::where("refid", $t->id)->where("type", 0)->first(); }
            if (!array_key_exists($ISOcode, $curjar)) {
                if ($type == 0) { $curjar[$ISOcode] = ($t->value + $fee->value); }
                else { $curjar[$ISOcode] = $t->value; }
                continue;
            }
            if ($type == 0) { $curjar[$ISOcode] += ($t->value + $fee->value); }
            else { $curjar[$ISOcode] += $t->value; }
        }
        return $curjar;
    }

    public function index(Request $request) {
        $date  = new \DateTime('today');
        $month = $date->format('m');
        $year  = $date->format('Y');
        $day   = $date->format('d');
        $accounts   = Account::where("user_id", Auth::user()->id)->get();
        $rates      = Storage::disk('public')->get('currency_rates.json');
        $rates      = json_decode($rates, true);
        $moneyins   = $moneyouts = $pmoneyins = $pmoneyouts = $defbalances = $balancealloc = array();
        $moneyin    = $moneyout  = $pmoneyin  = $pmoneyout  = $tdefbalance = 0;
        $defaultcur = Currency::where("ISOcode", "USD")->first();
        foreach ($accounts as $account) {
            if ($request->summaryperiod == 0) {
                # monthly
                $credits  = Transaction::where("acc_id", $account->id)->where('type', 1)->where('status', 1)->whereMonth("created_at", "=", $month)->whereYear("created_at", "=", $year)->get();
                $debits   = Transaction::where("acc_id", $account->id)->where('type', 0)->where('status', 1)->whereMonth("created_at", "=", $month)->whereYear("created_at", "=", $year)->get();
                $pcredits = Transaction::where("acc_id", $account->id)->where('type', 1)->where('status', 0)->whereMonth("created_at", "=", $month)->whereYear("created_at", "=", $year)->get();
                $pdebits  = Transaction::where("acc_id", $account->id)->where('type', 0)->where('status', 0)->whereMonth("created_at", "=", $month)->whereYear("created_at", "=", $year)->get();
            } else if ($request->summaryperiod == 1) {
                # week
                $credits  = Transaction::where("acc_id", $account->id)->where('type', 1)->where('status', 1)->whereBetween('created_at', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()])->get();
                $debits   = Transaction::where("acc_id", $account->id)->where('type', 0)->where('status', 1)->whereBetween('created_at', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()])->get();
                $pcredits = Transaction::where("acc_id", $account->id)->where('type', 1)->where('status', 0)->whereBetween('created_at', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()])->get();
                $pdebits  = Transaction::where("acc_id", $account->id)->where('type', 0)->where('status', 0)->whereBetween('created_at', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()])->get();
            } else if ($request->summaryperiod == 2) {
                # day
                $credits  = Transaction::where("acc_id", $account->id)->where('type', 1)->where('status', 1)->whereMonth("created_at", "=", $month)->whereYear("created_at", "=", $year)->whereDay("created_at", "=", $day)->get();
                $debits   = Transaction::where("acc_id", $account->id)->where('type', 0)->where('status', 1)->whereMonth("created_at", "=", $month)->whereYear("created_at", "=", $year)->whereDay("created_at", "=", $day)->get();
                $pcredits = Transaction::where("acc_id", $account->id)->where('type', 1)->where('status', 0)->whereMonth("created_at", "=", $month)->whereYear("created_at", "=", $year)->whereDay("created_at", "=", $day)->get();
                $pdebits  = Transaction::where("acc_id", $account->id)->where('type', 0)->where('status', 0)->whereMonth("created_at", "=", $month)->whereYear("created_at", "=", $year)->whereDay("created_at", "=", $day)->get();
            } else if ($request->summaryperiod == 3) {
                # year
                $credits  = Transaction::where("acc_id", $account->id)->where('type', 1)->where('status', 1)->whereYear("created_at", "=", $year)->get();
                $debits   = Transaction::where("acc_id", $account->id)->where('type', 0)->where('status', 1)->whereYear("created_at", "=", $year)->get();
                $pcredits = Transaction::where("acc_id", $account->id)->where('type', 1)->where('status', 0)->whereYear("created_at", "=", $year)->get();
                $pdebits  = Transaction::where("acc_id", $account->id)->where('type', 0)->where('status', 0)->whereYear("created_at", "=", $year)->get();
            } else if ($request->summaryperiod == 4) {
                # all
                $credits  = Transaction::where("acc_id", $account->id)->where('type', 1)->where('status', 1)->get();
                $debits   = Transaction::where("acc_id", $account->id)->where('type', 0)->where('status', 1)->get();
                $pcredits = Transaction::where("acc_id", $account->id)->where('type', 1)->where('status', 0)->get();
                $pdebits  = Transaction::where("acc_id", $account->id)->where('type', 0)->where('status', 0)->get();
            }
            $code         = Currency::find($account->currencyid);
            $moneyins     = $this->sumtype($credits,  1, $account, $code->ISOcode, $moneyins);
            $moneyouts    = $this->sumtype($debits,   0, $account, $code->ISOcode, $moneyouts);
            $pmoneyins    = $this->sumtype($pcredits, 1, $account, $code->ISOcode, $pmoneyins);
            $pmoneyouts   = $this->sumtype($pdebits,  0, $account, $code->ISOcode, $pmoneyouts);
            $defbalance   = $account->balance() * $rates[$code->ISOcode][$defaultcur->ISOcode];
            $tdefbalance += $defbalance;
            if (!array_key_exists($code->ISOcode, $defbalances)) { $defbalances[$code->ISOcode] = $defbalance; continue; }
            $defbalances[$code->ISOcode] += $defbalance;
        }
        $summary = function($all, &$target, $rates, $defcur) { foreach ($all as $key => $value) { $target += ($value * $rates[$key][$defcur]); }};
        $summary($moneyins,   $moneyin,   $rates, $defaultcur->ISOcode);
        $summary($moneyouts,  $moneyout,  $rates, $defaultcur->ISOcode);
        $summary($pmoneyins,  $pmoneyin,  $rates, $defaultcur->ISOcode);
        $summary($pmoneyouts, $pmoneyout, $rates, $defaultcur->ISOcode);
        foreach ($defbalances as $key => $value) { $balancealloc[$key] = ($value / $tdefbalance) * 100; }
        $transactions = Transaction::where('user_id', Auth::id())
            ->doesntHave('origintx')
            ->orWhereHas('origintx', function ($t) { $t->where('user_id', '!=', Auth::id()); })
            ->where('user_id', Auth::id())->orderByDesc("created_at")->paginate(25);
        $exhscheme  = Storage::disk('public')->get('currency_rates.json');
        $exhscheme  = json_decode($exhscheme, true);
        $currencies = Currency::where("status", 1)->get();
        return view('user.dashboard',compact('currencies', 'exhscheme', 'moneyin', 'moneyout', 'pmoneyin', 'pmoneyout', 'balancealloc', 'defaultcur', 'accounts', 'transactions'));
    }

    public function update_avatar(Request $request) {
        //TODO validate image type
        //TODO deny or transform gif type
        if ($request->file("file")->isValid()) {
            //search & delete current avatar
            //$accnum = Auth::user()->account_number;
            $prefix = "script/storage/app/avatar/".Auth::user()->username;
            $exts   = [".jpg", ".jpeg", ".png", ".bmp"];
            foreach ($exts as $ext) { if (file_exists($prefix.$ext)) { unlink($prefix.$ext); }}
            // save new avatar
            $filename = Auth::user()->username.".".$request->file("file")->extension();
            $path = Storage::putFileAs('avatar/', $request->file("file"), $filename);
            return json_encode(array("filename" => $filename, "success" => true, "error" => null));
        }
        return json_encode(array("success" => false, "error" => "Uploaded file is not valid"));
    }

    public function transactionTrxReport(Request $request) {
        switch ($request->type) {
            case 'trxid':
                $transactions = Transaction::where("user_id", Auth::user()->id)->where('trxid', 'LIKE', "%$request->value%")->paginate(20);
                return view('admin.transaction.all_transaction', compact('transactions'));
                break;
            case 'name':
                $transactions = Transaction::where("user_id", Auth::user()->id)->where('nameref', 'LIKE', "%$request->value%")->paginate(20);
                return view('admin.transaction.all_transaction', compact('transactions'));
                break;
            default:
                break;
        }
    }

    public function notifaction_list() {
        $notifications = auth()->user()->unreadNotifications;
        return view('user.notification.index', compact('notifications'));
    }
    
    public function markNotification(Request $request) {
        auth()->user()
            ->unreadNotifications
            ->when($request->input('id'), function ($query) use ($request) {
                return $query->where('id', $request->input('id'));
            })
            ->markAsRead();
        $nbr_notif = Auth::user()->unreadNotifications->count()-1;
        return json_encode(array("nbr_notif" =>  $nbr_notif, "success" => true, "error" => null));
    }
}

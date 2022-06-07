<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use App\Models\WireData;
use App\Models\Currency;
use App\Models\Account;
use App\Models\User;
use App\Models\Withdraw;
use App\Models\Support;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Auth;

class DashboardController extends Controller
{
    // Admin Dashboard 
    public function index() {
    	if (!Auth()->user()->can('dashboard.index')) { return abort(401); }
        # Prep
        $dfcur  = "EUR";
        $rates  = Storage::disk('public')->get('currency_rates.json');
        $rates  = json_decode($rates, true);
        $currs  = Currency::where("status", 1)->get();
        $wiredebits = $wirecredits   = $interndebits   = $pwirecreditsvalue = $pwiredebitsvalue = $totalfunds = 0;
        $currencies = $curwiredebits = $curwirecredits = array();
        foreach ($currs as $c) { $currencies[$c->ISOcode] = array($c->symbol, $c->fullname);  $curwiredebits[$c->ISOcode] = 0; $curwirecredits[$c->ISOcode] = 0; }
        
        # Total/Pending Wire Transactions
        $dwires = WireData::where("type", 0)->where("status", 1)->select("*", DB::raw("SUM(_32a_value) as cur_value"))->groupBy('_32a_currency')->get();
        $cwires = WireData::where("type", 1)->where("status", 1)->select("*", DB::raw("SUM(_32a_value) as cur_value"))->groupBy('_32a_currency')->get();
        foreach ($dwires as $dw) { $curwiredebits[$dw->_32a_currency]  = $dw->cur_value; $wiredebits  += ($dw->cur_value * $rates[$dw->_32a_currency][$dfcur]); }
        foreach ($cwires as $dw) { $curwirecredits[$dw->_32a_currency] = $dw->cur_value; $wirecredits += ($dw->cur_value * $rates[$dw->_32a_currency][$dfcur]); }
        
        # Total Internal Transactions
        $dtxs = Transaction::where("type", 0)->where("status", 1)->select("*", DB::raw("SUM(value) as cur_value"))->groupBy('currencyid')->get();
        foreach ($dtxs as $tx) { $interndebits += ($tx->cur_value * $rates[$tx->currency()->ISOcode][$dfcur]); }
        
        # Pending Transactions 
        $pdebits      = Transaction::where("type", 0)->where("status", 0)->count();
        $pwirecredits = WireData::where("type", 1)->where("status", 0)->count();
        
        # Total Pending Wire Transactions
        $dwires = WireData::where("type", 0)->where("status", 0)->select("*", DB::raw("SUM(_32a_value) as cur_value"))->groupBy('_32a_currency')->get();
        foreach ($dwires as $dw) { $pwiredebitsvalue += ($dw->cur_value * $rates[$dw->_32a_currency][$dfcur]); }
        $cwires = WireData::where("type", 1)->where("status", 0)->select("*", DB::raw("SUM(_32a_value) as cur_value"))->groupBy('_32a_currency')->get();
        foreach ($cwires as $dw) { $pwirecreditsvalue += ($dw->cur_value * $rates[$dw->_32a_currency][$dfcur]); }
        
        # Total Funds (todo: maybe cache to cut calc overhead)
        $accs   = Account::get();
        foreach ($accs as $acc) { $totalfunds += ($acc->balance() * $rates[$acc->currency->ISOcode][$dfcur]); }
        
        # Users (todo: make verified based on docs)
        $countusers    = User::where('role_id', 2)->count();
        $avusers       = User::where('role_id', 2)->where('status', 1)->count();
        $unverifusers  = User::where('role_id', 2)->where('email_verified_at', null)->where('status', 1)->count();
        # Support
        $opensupport   = Support::where("status", 0)->count();
        $closedsupport = Support::where("status", 1)->count();
        
        return view('admin.dashboard', compact('countusers', 'avusers', 'unverifusers', 'totalfunds', 'pwiredebitsvalue', 'pwirecreditsvalue', 'pdebits', 'pwirecredits', 'currencies', 'opensupport', 'closedsupport', 'wiredebits', 'wirecredits', 'interndebits', 'curwiredebits', 'curwirecredits'));
    }
}

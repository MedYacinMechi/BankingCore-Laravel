<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use App\Models\WireData;
use App\Models\User;
use App\Models\Account;
use Illuminate\Http\Request;

class TransactionController extends Controller
{
    
    public function create() {
        if (!Auth()->user()->can('transaction')) {
           return abort(401);
        }
        return view('admin.transaction.create');
    }

    public function index() {
        if (!Auth()->user()->can('transaction')) {
            return abort(401);
        }
        $transactions = Transaction::orderByDesc("created_at")->paginate(50);
        $wires = WireData::where("type", 1)->orderByDesc("created_at")->paginate(50);
        return view('admin.transactions.index', compact('transactions', 'wires'));
    }

    public function view($id) {
        if (!Auth()->user()->can('transaction')) {
            return abort(401);
         }
        $transaction = Transaction::findOrFail($id);
        return view('admin.transactions.view', compact('transaction'));
    }

    public function deposit() {
        # FOLD
        return view('admin.transactions.deposit');
    }

    public function makedeposit(Request $request) {
        if (!is_numeric($request->tvalue)) { return redirect()->back()->with("error", "Invalid Amount"); }
        if ($request->tvalue == 0) { return redirect()->back()->with("error", "Invalid Amount"); }
        $account = Account::where("identifier", $request->accountnum)->first();
        if ($account == null) { return redirect()->back()->with("error", "Account does not exist"); }
        $t = new Transaction();
        $t->acc_id     = $account->id;
        $t->user_id    = $account->user_id;
        $t->value      = $request->tvalue;
        $t->currencyid = $account->currencyid;
        $t->type       = 1;
        $t->deposit    = 1;
        $t->status     = 1;
        $t->nameref    = $account->user->firstname." ".$account->user->lastname." ".$account->user->username;
        $t->save();
        return redirect()->back()->with("success", "Amount Deposited Successfully");
    }

    // All Transaction Search report
    public function allTransactionSearchReport(Request $request) {
        $start_date   = $request->start_date . " 00:00:00";
        $end_date     = $request->end_date . " 23:59:59";
        $transactions = Transaction::whereBetween('created_at', [$start_date, $end_date])->paginate(20);
        return view('admin.transaction.all_transaction', compact('transactions', 'start_date', 'end_date'));
    }

    // All Transaction Trx report
    public function allTransactionTrxReport(Request $request) {
        switch ($request->type) {
            case 'trxid':
                $transactions = Transaction::where('trxid', 'LIKE', "%$request->value%")->paginate(20);
                return view('admin.transaction.all_transaction', compact('transactions'));
                break;
            case 'name':
                $transactions = Transaction::where('nameref', 'LIKE', "%$request->value%")->paginate(20);
                return view('admin.transaction.all_transaction', compact('transactions'));
                break;
            default:
                break;
        }
    }

}

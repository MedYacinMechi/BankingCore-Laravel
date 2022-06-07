<?php

namespace App\Http\Controllers\User;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Transaction;
use App\Models\Account;
use Auth;

class AccountsController extends Controller {

    public function view($id)
    {
        $account = Account::findOrFail($id);
        if ($account->user_id != Auth::user()->id) { abort(403); }
        $transactions = Transaction::where("acc_id", $account->id)->orderBy("created_at")->paginate(25);
        return view('user.accounts.view', compact("account", "transactions"));
    }

}

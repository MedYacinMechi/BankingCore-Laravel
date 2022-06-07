<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Currency;
use App\Models\User;
use App\Models\Account;
use App\Models\AccountType;
use App\Models\Hold;
use App\Models\Transaction;
use Illuminate\Http\Request;

class AccountsController extends Controller
{
    public function index(Request $request) {
        $sortschema = ["2" => "identifier", "3" => "type", "4" => "currencyid"];
        $sortby     = $request->input("sortby");
        if ($request->input("sortby") == 1) { 
            $accounts = Account::select('*')
                ->join('users', 'accounts.user_id', '=', 'users.id')
                ->orderBy('users.firstname', 'ASC')
                ->select('accounts.*')->paginate(25);
        } elseif ((1 <= $sortby) && ($sortby <= 4)) { 
            $accounts = Account::orderBy($sortschema[$request->input("sortby")], 'ASC')->paginate(25);
        } else { $accounts = Account::orderBy("created_at", "DESC")->paginate(25); }
        return view('admin.accounts.index', compact("accounts"));
    }

    public function searchaccount(Request $request) {
        $accounts = Account::latest()->paginate(25);
        $keyword  = $request->keyword;
        if (!empty($keyword)       && $request->column == 0 && $request->column !== null) {
            $accounts = Account::whereHas('currency', function ($query) use($keyword) { return $query->where("ISOcode", 'LIKE', "%$keyword%"); })->paginate(25);
        } elseif (!empty($keyword) && $request->column == 1) {
            $keyword  = "%$keyword%";
            $accounts = Account::whereHas('user', function ($query) use($keyword) { return $query->whereRaw("concat(firstname, ' ', lastname, ' ', companyname) like ?", [$keyword]); })->paginate(25);
        } elseif (!empty($keyword) && $request->column == 2) {
            $accounts = Account::where("identifier", "LIKE", "%$keyword%")->paginate(25);
        } elseif (!empty($keyword) && $request->column == 3) {
            $accounts = Account::whereHas('acctype', function ($query) use($keyword) { return $query->where("name", 'LIKE', "%$keyword%"); })->paginate(25);
        }
        return view('admin.accounts.index', compact("accounts"));
    }

    public function view($id) {
        $account = Account::findOrFail($id);
        $transactions = Transaction::where("acc_id", $account->id)->orderBy("created_at")->paginate(25);
        return view('admin.accounts.view', compact("account", "transactions"));
    }

    public function newaccount() {
        $currencies = Currency::get();
        $users = User::get();
        $acctypes = AccountType::get();
        return view('admin.accounts.new_account', compact("users","acctypes","currencies"));
    }

    public function addnewaccount(Request $request) {
        if ($request->user == '1' || empty($request->user)) { return redirect()->back()->with('error', 'Client name (Company) cannot be empty'); }
        $currency = Currency::where("ISOcode", $request->currency)->first();
        if ($currency == null) { return redirect()->back()->with('error', 'Invalid Currency'); }
        $account                = new Account();
        $account->status        = 1;
        $account->identifier    = 'PTU3X393VG3ZXQRIV0000';
        $account->type          = $request->acctype;
        $account->user_id       = $request->user;
        $account->currencyid    = $currency->id;
        $account->save();

        if ($request->acctype == 4){
            $hold                   = new Hold();
            $hold->interest         = 1001;
            $hold->interest_type    = 1;
            $hold->acc_id           = 3;
            $hold->user_id          = 3;
            $hold->save();
        }

        $transaction             = new Transaction();
        $transaction->acc_id     = Account::where('identifier', 'PTU3X393VG3ZXQRIV0000')->value('id');
        $transaction->user_id    = $request->user;
        $transaction->value      = $request->initialBalance;
        $transaction->currencyid = $currency->id;
        $transaction->type       = 1;
        $transaction->deposit    = 1;
        $transaction->status     = 1;
        $transaction->nameref    = User::where('id', $request->user)->value('firstname')." ".User::where('id', $request->user)->value('lastname');
        // $transaction->save();
        return redirect()->back()->with('success', 'Account created successfully');
    }

    public function types() {
        $acctypes = AccountType::whereRaw("1 = 1")->paginate(25);
        return view('admin.accounts.types', compact("acctypes"));
    }

    public function newtype() {
        $currencies = Currency::get();
        return view('admin.accounts.create_type', compact("currencies"));
    }

    public function editacctype(Request $request) {
        $currencies = Currency::get();
        $acctype    = AccountType::where("id", $request->id)->first(); // todo:404
        return view('admin.accounts.edit_type', compact("acctype", "currencies"));
    }

    public function addnewtype(Request $request) {
        if (empty($request->acctypename)) { return redirect()->back()->with('error', 'Account type name cannot be empty'); }
        $currency = Currency::where("ISOcode", $request->currency)->first();
        if ($currency == null) { return redirect()->back()->with('error', 'Invalid Currency'); }
        $request->monthlyfee = empty($request->monthlyfee) ? 0 : $request->monthlyfee;
        if (!is_numeric($request->monthlyfee)) { return redirect()->back()->with('error', 'Invalid Monthly Fee'); }
        $acctype                = new AccountType();
        $acctype->type          = 0;
        $acctype->name          = $request->acctypename;
        $acctype->currencyid    = $currency->id;
        $acctype->monthlyfee    = $request->monthlyfee;
        $acctype->monthlyfee30d = $request->has("monthlyfee30d");
        if ($request->has("MB_checkbox")) { return $this->acctype_minbalance($request, $acctype); }
        if ($request->has("CL_checkbox")) { return $this->acctype_creditline($request, $acctype); }
        if ($request->has("IG_checkbox")) { return $this->acctype_interestgen($request, $acctype); }
        if ($request->has("TD_checkbox")) { return $this->acctype_termdeposit($request, $acctype); }
        $acctype->save();
        return redirect()->back()->with('success', 'Account type created successfully');
    }

    public function updateacctype(Request $request) {
        if (empty($request->acctypename)) { return redirect()->back()->with('error', 'Account type name cannot be empty'); }
        $currency = Currency::where("ISOcode", $request->currency)->first();
        if ($currency == null) { return redirect()->back()->with('error', 'Invalid Currency'); }
        $request->monthlyfee = empty($request->monthlyfee) ? 0 : $request->monthlyfee;
        if (!is_numeric($request->monthlyfee)) { return redirect()->back()->with('error', 'Invalid Monthly Fee'); }
        $acctype                = AccountType::where("id", $request->id)->first();
        if ($acctype == null) { return redirect()->back()->with('error', 'Cannot find account type'); }
        $acctype->name          = $request->acctypename;
        $acctype->currencyid    = $currency->id;
        $acctype->monthlyfee    = $request->monthlyfee;
        $acctype->monthlyfee30d = $request->has("monthlyfee30d");
        if ($request->acctypeid == 1) { return $this->acctype_minbalance($request,  $acctype, "updated"); }
        if ($request->acctypeid == 2) { return $this->acctype_creditline($request,  $acctype, "updated"); }
        if ($request->acctypeid == 3) { return $this->acctype_interestgen($request, $acctype, "updated"); }
        if ($request->acctypeid == 4) { return $this->acctype_termdeposit($request, $acctype, "updated"); }
        $acctype->save();
        return redirect()->back()->with('success', 'Account type updated successfully');
    }

    private function acctype_minbalance($request, $acctype, $notif="created") {
        if (!is_numeric($request->MB_limit)) { return redirect()->back()->with('error', 'Invalid Limit Amount'); }
        $MB_force = $request->has("MB_force");
        if (!is_numeric($request->MB_fee))   { return redirect()->back()->with('error', 'Invalid Minimum Balance Fee Amount'); }
        $acctype->type    = 1;
        $acctype->limit   = $request->MB_limit;
        $acctype->fee     = $request->MB_fee;
        $acctype->mbforce = $MB_force;
        $acctype->save();
        return redirect()->back()->with('success', 'Account type '.$notif.' successfully');
    }

    private function acctype_creditline($request, $acctype, $notif="created") {
        if (!is_numeric($request->CL_limit))        { return redirect()->back()->with('error', 'Invalid Line of Credit Limit Amount'); }
        if (!is_numeric($request->CL_rate))         { return redirect()->back()->with('error', 'Invalid Annual Interest Rate'); }
        if (!is_numeric($request->CL_comperiod))    { return redirect()->back()->with('error', 'Invalid Compounding Period'); }
        if ($request->CL_comperiod > 4)             { return redirect()->back()->with('error', 'Invalid Compounding Period'); }
        if (!is_numeric($request->CL_method))       { return redirect()->back()->with('error', 'Invalid Method'); }
        if (!is_numeric($request->CL_chargeperiod)) { return redirect()->back()->with('error', 'Invalid Charge Period'); }
        if ($request->CL_chargeperiod < $request->CL_comperiod || $request->CL_chargeperiod > 4) { return redirect()->back()->with('error', 'Invalid Charge Period'); }
        $acctype->type        = 2;
        $acctype->limit       = $request->CL_limit;
        $acctype->annualrate  = $request->CL_rate;
        $acctype->compounding = $request->CL_comperiod;
        $acctype->interval    = $request->CL_chargeperiod;
        $acctype->method      = 0;
        $acctype->save();
        return redirect()->back()->with('success', 'Account type '.$notif.' successfully');
    }

    private function acctype_interestgen($request, $acctype, $notif="created") {
        if (!is_numeric($request->IG_rate))         { return redirect()->back()->with('error', 'Invalid Annual Interest Rate'); }
        if (!is_numeric($request->IG_comperiod))    { return redirect()->back()->with('error', 'Invalid Compounding Period'); }
        if ($request->IG_comperiod > 4)             { return redirect()->back()->with('error', 'Invalid Compounding Period'); }
        if (!is_numeric($request->IG_method))       { return redirect()->back()->with('error', 'Invalid Method'); }
        if ($request->IG_method != 0 && $request->IG_method != 1) { return redirect()->back()->with('error', 'Invalid Method'); }
        if (!is_numeric($request->IG_payoutperiod)) { return redirect()->back()->with('error', 'Invalid Payout Period'); }
        if ($request->IG_payoutperiod < $request->IG_comperiod || $request->IG_payoutperiod > 4) { return redirect()->back()->with('error', 'Invalid Payout Period'); }
        $acctype->type        = 3;
        $acctype->annualrate  = $request->IG_rate;
        $acctype->compounding = $request->IG_comperiod;
        $acctype->interval    = $request->IG_payoutperiod;
        $acctype->method      = $request->IG_method;
        $acctype->save();
        return redirect()->back()->with('success', 'Account type '.$notif.' successfully');
    }

    private function acctype_termdeposit($request, $acctype, $notif="created") {
        if (empty($request->TD_status))          { return redirect()->back()->with('error', 'Term status cannot be empty'); }
        if (!is_numeric($request->TD_interests)) { return redirect()->back()->with('error', 'Invalid Interests'); }
        if (!is_numeric($request->TD_term))      { return redirect()->back()->with('error', 'Invalid Term'); }
        $acctype->type       = 4;
        $acctype->termstatus = $request->TD_status;
        $acctype->interests  = $request->TD_interests;
        $acctype->term       = $request->TD_term;
        $acctype->save();
        return redirect()->back()->with('success', 'Account type '.$notif.' successfully');
    }

}

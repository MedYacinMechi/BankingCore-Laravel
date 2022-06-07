<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Jobs\SendEmailJob;
use App\Mail\TransactionMail;
use App\Mail\UserLoginOtp;
use App\Models\Transaction;
use App\Models\User;
use App\Models\Account;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\str;
use Illuminate\Support\Facades\Session;
use Barryvdh\DomPDF\Facade as PDF;
use PragmaRX\Google2FA\Google2FA;
use Illuminate\Support\Facades\Storage;
use File;

class UserController extends Controller
{

    # <MBI>

    public function index() {
        if (!Auth()->user()->can('user.index')) { return abort(401); }
        $users = User::where('role_id', 2)->paginate(50);
        return view('admin.user.index', compact('users'));
    }

    public function show($id) {
        if (!Auth()->user()->can('user.show')) { return abort(401); }
        $user_id = $id;
        $user = User::findOrFail($id);
        $accounts = Account::where("user_id", $id)->get();
        $transactions = Transaction::where('user_id', $id)
            ->doesntHave('origintx')
            ->orWhereHas('origintx', function ($t) use($id) { $t->where('user_id', '!=', $id); })
            ->where('user_id', $id)->orderByDesc("created_at")->paginate(50);
        $docspath = storage_path("app/docs/".$user->id);
        $hasdocs  = is_dir($docspath) ? !empty(File::files($docspath)) : false;
        return view('admin.user.view', compact('transactions', 'accounts', 'user', 'hasdocs'));
    }

    public function getuserdocs($id) {
        $user  = User::findOrFail($id);
        $zip   = new \ZipArchive();
        $fname = time().".zip";
        $dir   = storage_path("app/docs/".$user->id);
        if ($zip->open($dir."/".$fname, \ZipArchive::CREATE) == TRUE) {
            $files = File::files(storage_path("app/docs/".$user->id));
            if (empty($files)) { abort(404); }
            foreach ($files as $key => $value) {
                if ($value->getExtension() == "zip") { continue; }
                $relativeName = basename($value);
                $zip->addFile($value, $relativeName);
            }
            $zip->close();
        }
        return response()->download($dir."/".$fname);
    }

    public function setkycstatus(Request $request) {
        $user = User::findOrFail($request->id);
        $user->docs_verified_at = $request->status == 1 ? time() : null;
        $user->save();
        return redirect()->back()->with("success", "KYC updated successfully");
    }

    public function setstatus(Request $request) {
        $user = User::findOrFail($request->id);
        $user->status = $request->status == 1 ? 1 : 0;
        $user->save();
        $newstatus = ($user->status == 1 ? "enabled" : "disabled");
        return redirect()->back()->with("success", "User ".$user->firstname." ".$user->lastname." has been $newstatus");
    }

    public function searchuser(Request $request) {
        $users = User::latest()->paginate(25);
        $keyword  = $request->keyword;
        if (!empty($keyword)       && $request->column == 0 && $request->column !== null) {
            $keyword = "%$keyword%";
            $users   = User::whereRaw("concat(firstname, ' ', lastname, ' ', companyname) like ?", [$keyword])->paginate(25);
        } elseif (!empty($keyword) && $request->column == 1) {
            $keyword = "%$keyword%";
            $users   = User::where("username", "LIKE", $keyword)->paginate(25);
        } elseif (!empty($keyword) && $request->column == 2) {
            $keyword = "%$keyword%";
            $users   = User::where("email", "LIKE", $keyword)->paginate(25);
        } elseif (!empty($keyword) && $request->column == 3) {
            $keyword = "%$keyword%";
            $users   = User::whereRaw("concat(homephone, ' ', mobilephone, ' ', officephone, ' ', fax) like ?", [$keyword])->paginate(25);
        }
        return view('admin.user.index', compact('users'));
    }

    # </MBI>

    public function create()
    {
        if (!Auth()->user()->can('user.create')) {
            return abort(401);
         }
        return view('admin.user.create');
    }

    public function profile($id){
        if (Auth::user()->id != $id) {
            return abort(403);
        }
        $user_id  = $id;
        $user_transactions = User::findOrFail($id);
        return view('admin.user.profile', compact('user_transactions', 'user_id'));
    }

    public function profile_edit($id)
    {
        if (Auth::user()->id != $id) {
            return abort(403);
        }
        $user_edit = User::findOrfail($id);
        return view('admin.user.profile_edit', compact('user_edit'));
    }

    // Admin user Store
    public function store(Request $request)
    {
        // Validate
        $request->validate([
            'firstname'    => 'required',
            'lastname'     => 'required',
            'username'     => 'required',
            'email'        => 'required|email|unique:users,email',
            'phone_number' => 'required',
            'password'     => 'required|string|min:6|',
        ]);

        $check = User::where('username', $request->username)->first();
        if ($check == true) {
            return redirect()->back()->with('error', 'Username Already Exist');
        }

        $user_store = new User();
        $user_store->username  = $request->username;
        $user_store->firstname = $request->firstname;
        $user_store->lastname  = $request->lastname;
        $user_store->email     = $request->email;
        $user_store->password  = Hash::make($request->password);
        $user_store->phone     = $request->phone_number;
        $user_store->status    = $request->status;
        if ($request->email_verified_at == 'on') { $user_store->email_verified_at = date('Y-m-d H:i:s'); }
        if ($request->phone_verified_at == 'on') { $user_store->phone_verified_at = date('Y-m-d H:i:s'); }
        $user_store->save();
        return response()->json('User Added Successfully');
    }

    // User Edit
    public function edit($id)
    {
        if (!Auth()->user()->can('user.edit')) {
            return abort(401);
         }
        $user_edit = User::findOrfail($id);
        return view('admin.user.edit', compact('user_edit'));
    }

    //admin update
    public function profile_update(Request $request, $id)
    {
        if (Auth::user()->id != $id) {
            return abort(403);
        }
        // Validate
        $request->validate([
            'name'         => 'required',
            'email'        => 'required|email|unique:users,email,'.$id,
            'phone_number' => 'required',
            // 'password'     => 'required|string|min:6|',
        ]);

        // Account check
        $rend  = rand(100000, 888888) . rand(10000, 88888);
        $check = User::where('account_number', $rend)->first();
        if ($check == true) {
            return redirect()->back()->with('error', 'Account Number Already Exist');
        }

        // User update
        $user_update        = User::findOrFail($id);
        $user_update->name  = $request->name;
        $user_update->email = $request->email;

        if ($request->password != '') {
            $user_update->password = Hash::make($request->password);
        }

        $user_update->phone = $request->phone_number;
       
        if ($request->two_step_auth == 'on') {
            $user_update->two_step_auth = 1;
        } else {
            $user_update->two_step_auth = 0;
        }

        $user_update->save();

        return response()->json('User Updated Successfully');
    }

    // User Update
    public function update(Request $request, $id)
    {
        $check = User::where('username', $request->username)->first();
        if ($check == true) {
            return redirect()->back()->with('error', 'Username Already Exist');
        }
        $user_update = User::findOrFail($id);
        $user_update->username  = $request->username;
        $user_update->firstname = $request->firstname;
        $user_update->lastname  = $request->lastname;
        $user_update->status    = $request->status;
        $user_update->email     = $request->email;
        $user_update->phone     = $request->phone_number;
        if ($request->password) { $user_update->password = Hash::make($request->password); }
        if ($request->email_verified_at == 'on') { $user_update->email_verified_at = date('Y-m-d H:i:s'); }
        else { $user_update->email_verified_at = null; }
        if ($request->phone_verified_at == 'on') { $user_update->phone_verified_at = date('Y-m-d H:i:s'); }
        else { $user_update->phone_verified_at = null; }
        if ($request->two_step_auth == 'on') { $user_update->two_step_auth = 1; }
        else { $user_update->two_step_auth = 0; }
        if ($user_update->isenterprise == 1) { $user_update->companyname = $request->companyname; }
        $user_update->save();
        return response()->json('User Updated Successfully');
    }


    // User Delete
    public function destroy($id)
    {
        if (!Auth()->user()->can('user.delete')) {
            return abort(401);
        }
        $user_delete = User::findOrFail($id);
        $user_delete->delete();
        return redirect()->back()->with('success', 'Successfully Deleted');
    }

    // User Transaction mail
    public function userTransactionMail(Request $request, $id)
    {
        $request->validate([
            'subject' => 'required',
            'msg'     => 'required',
        ]);
        $user      = User::where('id', $id)->first();
        $user_mail = $user->email;
        $data      = [
            'email' => $user_mail,
            'subject' => $request->subject,
            'msg'     => $request->msg,
            'type'    => 'user_transaction_mail',
        ];
        // Send Mail
        if(env('QUEUE_MAIL') == 'on'){
            dispatch(new SendEmailJob($data));
        }else{
            Mail::to($user_mail)->send(new TransactionMail($data));
        }


        

        return response()->json('Mail Send Successfully');
    }

    public function transactionReport($id) {
        $user         = User::findOrFail($id);
        $transactions = Transaction::where('user_id', $id)->latest()->get();
        return view('admin.user.report', compact('transactions', 'user'))->with('i', 1);
    }

    public function jsonresponse($action, $error) {
        return json_encode(array("action" => $action, "error" => $error));
    }

    public function g2fa(Request $request) {
        //TODO: request validation
        $g2fats = $request->session()->get("g2faphase.ts", null);
        if ($g2fats == null) { return $this->jsonresponse(0, "Not authenticated, login again"); }
        if ((time() - $g2fats) > 60) {
            $request->session()->forget(['g2faphase.ts', 'g2faphase.uid']);
            return $this->jsonresponse(0, "Authentication timed out, login again");
        }
        $uid  = $request->session()->get("g2faphase.uid");
        $user = User::where('id', $uid)->first();
        if ($user == null) { $this->jsonresponse(0, "Invalid user id"); }
        $google2fa = new Google2FA();
        //MPPZ7S2NTK7S2ZRE
        if ($google2fa->verifyKey($user->g2fakey, $request->g2fa)) {
            $request->session()->forget(['g2faphase.ts', 'g2faphase.uid']);
            Auth::loginUsingId($uid);
            return $this->jsonresponse(1, null);
        } else { $this->jsonresponse(0, "Invalid authentication code"); }
    }
    
    public function login(Request $request) {
        //TODO: request validation
        // this secret key will be stored where admin can edit it
        $secret = '6LeIxAcTAAAAAGG-vFI1TnRWxMZNFuojJ4WifJWe'; // 6Lf0do0eAAAAAPubylj9JiBd7EcvuadHDWSQ4mh5
        $ch = curl_init();
        $gverifyurl = 'https://www.google.com/recaptcha/api/siteverify';
        curl_setopt($ch, CURLOPT_URL, $gverifyurl.'?secret='.$secret.'&response='.$request->recaptcha);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        //TODO: remove those in production !localhost
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        // ******************************************

        $response = curl_exec($ch);
        curl_close($ch);
        $response = json_decode($response);
        if(!$response->success) { return $this->jsonresponse(0, "reCAPTHCA verification failed"); }
        $user = User::where('email', $request->email)->first();
        if ($user == null || !Hash::check($request->password, $user->password)) {
            return $this->jsonresponse(0, "Incorrect credentials");
        }
        if ($user->g2fakey != null && $user->g2fakey != "") {
            $request->session()->put('g2faphase.ts', time());
            $request->session()->put('g2faphase.uid', $user->id);
            return $this->jsonresponse(2, null);
        }
        Auth::loginUsingId($user->id);
        return $this->jsonresponse(1, null);
    }

    // user Otp for login
    public function userOtp()
    {
        
        $user = User::findOrFail(Auth::id());
        $data['otp_number'] = $otp = rand(1000, 9999);
        $data['type'] = 'login_otp';
        $data['email'] = $user->email;
        Session::put('otp_number', $otp);
        Session::put('message', "Check your mail for otp!");
        // dd(env('QUEUE_MAIL'));


        if (env('QUEUE_MAIL') == 'on') {
            dispatch(new SendEmailJob($data));
        }else{
            Mail::to($user->email)->send(new UserLoginOtp($data));
        }
        
        return redirect()->route('user.otp.view');
    }

    public function userOtpView(){
        return view('user.userotp.login_otp');
    }

    public function profileOtp()
    {
        $user = User::findOrFail(Auth::id());
        $data['otp_number'] = $otp = rand(1000, 9999);
        $data['type'] = 'login_otp';
        $data['email'] = $user->email;

        Session::put('otp_number', $otp);
        Session::put('message', "Check your mail for otp!");

        if (env('QUEUE_MAIL') == 'on') {
            dispatch(new SendEmailJob($data));
        }else{
            Mail::to($user->email)->send(new UserLoginOtp($data)); 
        }
        
        return redirect()->route('profile.otp.view');
    }

    public function profileOtpView(){
        return view('admin.user.profile_otp');
    }
    // profile OtP confirmation
    public function profileOtpConfirmation(Request $request)
    {
        if($request->otp != Session::get('otp_number')) {
            Session::put('message', "OTP not matched");
            return redirect()->route('profile.otp.view');
        } else{
            if (Session::has('message')) {
                Session::forget('message');
            } 
            Session::put('otp_verified', true);
            Session::forget('otp_number');
            return redirect('/admin/dashboard');
        }
        
    }

    // Login OtP confirmation
    public function userOtpConfirmation(Request $request)
    {
        if($request->otp != Session::get('otp_number')) {
            Session::put('error', "OTP not matched");
            return redirect()->route('user.otp.view');
        } else{
            if (Session::has('message')) {
                Session::forget('message');
            } 
            Session::put('otp_verified', true);
            Session::forget('otp_number');
            return redirect('/user/dashboard');
        }
        
    }

    // Heartbeat
    public function heartbeat(Request $request) {
        $user = User::findOrFail(Auth::id());
        $user->last_active = time();
        $user->save();
    }


}

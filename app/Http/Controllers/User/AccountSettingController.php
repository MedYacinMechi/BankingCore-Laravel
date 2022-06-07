<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Jobs\SendEmailJob;
use App\Mail\PasswordChangeOtp;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Session;
use PragmaRX\Google2FA\Google2FA;
use Illuminate\Support\Facades\Storage;

class AccountSettingController extends Controller
{

    /* <MBI> */
    private function verifieduser_update($user, $request) {
        if (!empty($request->username) && $user->username != $request->username) {
            $un = User::where("username", $request->username)->first();
            if ($un != null) { return redirect()->back()->with('error', 'Username "'.$request->username.'" already exists'); }
            $user->username = $request->username; }
        if (!empty($request->email) && $user->email != $request->email) {
            $email = User::where("email", $request->email)->first();
            if ($email != null) { return redirect()->back()->with('error', 'Email "'.$request->email.'" already exists'); }
            $user->email = $request->email;
            /* todo: confirm the new email */ }
        if (!empty($request->password)) { $user->password = Hash::make($request->password); }
        /* todo: confirm the new mobile number */
        if (!empty($request->intlmobile)) { $user->mobilephone = $request->intlmobile; }
        if (!empty($request->intlhome))   { $user->homephone   = $request->intlhome; }
        if (!empty($request->intloffice)) { $user->officephone = $request->intloffice; }
        if (!empty($request->intlfax))    { $user->fax         = $request->intlfax; }
        return $user;
    }

    private function unverifieduser_update($user, $request) {
        $docfileput = function($arr, $r, $id) {
            foreach ($arr as $fn) {
                if ($r->hasFile($fn) && $r->file($fn)->isValid()) {
                    $filename = $fn.".".$r->file($fn)->extension();
                    $path = Storage::putFileAs('docs/'.$id, $r->file($fn), $filename);
            }}};
        $user = $this->verifieduser_update($user, $request);
        if (!empty($request->firstname))    { $user->firstname    = $request->firstname; }
        if (!empty($request->lastname))     { $user->lastname     = $request->lastname; }
        if (!empty($request->birthdate))    { $user->birthdate    = $request->birthdate; }
        if (!empty($request->residence))    { $user->residence    = $request->residence; }
        if (!empty($request->citizenship))  { $user->citizenship  = $request->citizenship; }
        if (!empty($request->city))         { $user->city         = $request->city; }
        if (!empty($request->address))      { $user->address      = $request->address; }
        if (!empty($request->address2))     { $user->address2     = $request->address2; }
        if (!empty($request->docnumber))    { $user->docnumber    = $request->docnumber; }
        if (!empty($request->docissuedate)) { $user->docissuedate = $request->docissuedate; }
        $validoct = $request->doctype == 0 || $request->doctype  == 1 || $request->doctype == 2;
        if ($validoct)                      { $user->doctype      = $request->doctype; }
        $docfileput(["doc1", "doc2"], $request, $user->id);
        return $user;
    }

    public function accountUpdate(Request $request) {
        # todo: further validation (passwd)/email/phone confirmation
        $docfileput = function($arr, $r, $id) {
            foreach ($arr as $fn) {
                if ($r->hasFile($fn) && $r->file($fn)->isValid()) {
                    $filename = $fn.".".$r->file($fn)->extension();
                    $path = Storage::putFileAs('docs/'.$id, $r->file($fn), $filename);
            }}};
        $user = Auth::user();
        if ($user->docs_verified_at != null) 
             { $user = $this->verifieduser_update($user, $request); }
        else { $user = $this->unverifieduser_update($user, $request); }
        if ($user->type == 1) {
            if (!empty($request->position))          { $user->position    = $request->position; }
            if (!empty($request->companyname))       { $user->companyname = $request->companyname; }
            if (!empty($request->companyregnumber))  { $user->companyregnumber  = $request->companyregnumber; }
            if (!empty($request->incorporationtype)) { $user->incorporationtype = $request->incorporationtype; }
            if (!empty($request->uk_eu_vat))         { $user->uk_eu_vat  = $request->uk_eu_vat; }
            if (!empty($request->launchdate))        { $user->launchdate = $request->launchdate; }
            $docfileput(["cdoc1", "cdoc2", "cdoc3", "cdoc4", "cdoc5", "cdoc6", "cdoc7", "cdoc8"], $request, $user->id);
        }
        $user->save();
        return redirect()->back()->with('success', 'Updated Successfully');
    }

    /* </MBI> */

    // Account Setting
    public function accountSetting()
    {
        if (Session::get('confirm') == 'user_update') {
            $hasg2fa = Auth::user()->g2fakey != null && Auth::user()->g2fakey != "";
            $google2fa = new Google2FA();
            $g2fakey = $hasg2fa ? Auth::user()->g2fakey : $google2fa->generateSecretKey();
            $qrcode  = $google2fa->getQRCodeUrl("TEST.inc", "test@gg.cc", $g2fakey);
            return view('user.accountsetting.user_update', array('qrcode' => $qrcode, 'g2fakey' => $g2fakey, 'hasg2fa' => $hasg2fa));
        } else {
            return view('user.accountsetting.password_confirmation');
        }
    }

    // Account Setting Confirmation
    public function accountSettingConfirmation(Request $request)
    {
        if (Auth::attempt(['id' => Auth::user()->id, 'password' => $request->password])) {
            Session::put('confirm', 'user_update');
            return redirect()->route('user.account.setting');
        } else {
            return redirect()->back()->with('error', 'Current Password Not Match');
        }

    }

    // Account Password Change View
    public function accountPasswordChange()
    {
        //
        return view('user.accountsetting.password_change');
    }

    // Account Password Update
    public function accountPasswordUpdate(Request $request)
    {
        if ($request->current_password) {

            $request->validate([
                'current_password' => 'required|password',
                'password'         => 'required|confirmed',
            ]);
        }
        $user = User::findOrFail(Auth::user()->id);
        //OTP
        $data['email'] = $user->email;
        $data['type'] = 'password_change';
        $data['confirmation_code'] = rand(1000, 9999);
        Session::put('confirmation_code', $data['confirmation_code']);
        Session::put('new_pass', $request->password);
        if (env('QUEUE_MAIL') == 'on') {
            dispatch(new SendEmailJob($data));
        }else{
            Mail::to($user->email)->send(new PasswordChangeOtp($data));
        }

        
        return redirect()->route('user.account.password.change.otp.view');
    }

    // Password change otp view
    public function accountPasswordOtpView()
    {
        //
        return view('user.userotp.password_update_otp');
    }

    // Password OTP RESEND
    public function accounOtpResend(Request $request)
    {
        $data['confirmation_code'] = rand(1000, 9999);
        Session::put('confirmation_code', $data['confirmation_code']);
        $user = User::findOrFail(Auth::user()->id);
        $data['type'] = 'password_change';
        $data['email'] = $user->email;
        if (env('QUEUE_MAIL') == 'on') {
            dispatch(new SendEmailJob($data));
        }else{
            Mail::to($user->email)->send(new PasswordChangeOtp($data));
        }
        // Mail::to($user->email)->send(new PasswordChangeOtp($data));
        
        return redirect()->route('user.account.password.change.otp.view');
    }

    // Account Password OTP Confirmation
    public function accountPasswordOtp(Request $request)
    {
        // Check
        if ($request->otp != Session::get('confirmation_code')) {
            return redirect()->back()->with('error', "OTP not matched");
        } else {
            $new_pass = Session::get('new_pass');
            $user_pass_update           = User::findOrFail(Auth::user()->id);
            $user_pass_update->password = Hash::make($new_pass);
            $user_pass_update->save();
            Session::forget('confirmation_code');
            Auth::logout();
            return redirect()->route('login')->with('success', 'Password Change Successfully');
        }
    }

    // Account Statement
    public function accountStatement()
    {
        $transactions = Transaction::where('user_id', Auth::id())->latest()->paginate(15);
        return view('user.accountsetting.account_statement', compact('transactions'));
    }

    // Account Statement Search
    public function accountStatementSearch(Request $request)
    {
        $start_date       = $request->start_date . " 00:00:00";
        $end_date         = $request->end_date . " 23:59:59";
        $search_statement = Transaction::where('user_id', Auth::id())->whereBetween('created_at', [$start_date, $end_date])->get();
        return view('user.accountsetting.search_statement', compact('search_statement', 'start_date', 'end_date'));
    }

     
}

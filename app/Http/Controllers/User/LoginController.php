<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Option;
use App\Models\User;
use Illuminate\Contracts\Session\Session as SessionSession;
use Illuminate\Support\Facades\Session;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Twilio\Rest\Client;

class LoginController extends Controller
{
    public function __construct()
    {
       $this->middleware('guest')->except(['phone_verification_resend','logout','phone2fa_view','phone_verification']);
    }
    
    public function register_form() {
        return view('user.register');
    }

    public function validatedate($date = '03/22/2010') {
        $arr = explode('/', $date);
        if (count($arr) != 3) { return false; }
        return checkdate($arr[0], $arr[1], $arr[2]);
    }

    public function register(Request $request) {
        # validation
        if ($request->formtype != "enterprise" && $request->formtype != "individual") { return redirect()->back()->with('error', 'Invalid form type'); }
        if (!preg_match("/^\w+$/", $request->firstname)) { return redirect()->back()->with('error', 'First name is not valid'); }
        if (!preg_match("/^\w+$/", $request->lastname))  { return redirect()->back()->with('error', 'Last name is not valid'); }
        if (strlen($request->username) < 6 || !preg_match("/^[A-Za-z0-9_]+$/", $request->username)) { return redirect()->back()->with('error', 'Username length must be equal to or higher than 6 characters and contains only alphanumeric and underscore _'); }
        if (!filter_var($request->email, FILTER_VALIDATE_EMAIL)) { return redirect()->back()->with('error', 'Email is not valid'); }
        if (strlen($request->password) < 8)     { return redirect()->back()->with('error', 'Password length must be equal to or higher than 8 characters'); }
        if (strlen($request->intlphone) < 9) { return redirect()->back()->with('error', 'Phone number is not valid'); }
        if ($request->formtype == "individual") {
            if (!$this->validatedate($request->birthdate)) { return redirect()->back()->with('error', 'Birth date is not valid'); }
            if (empty($request->occupation))    { return redirect()->back()->with('error', 'Occupation is not valid'); }
            if (empty($request->homeaddress))   { return redirect()->back()->with('error', 'Home address is not valid'); }
            if (empty($request->city))          { return redirect()->back()->with('error', 'City is not valid'); }
            if (empty($request->stateprovince)) { return redirect()->back()->with('error', 'State/Province is not valid'); }
            if (empty($request->zipcode))       { return redirect()->back()->with('error', 'Zipcode is not valid'); }
        } else {
            if (!$this->validatedate($request->launchdate)) { return redirect()->back()->with('error', 'Launch date is not valid'); }
            if (empty($request->position))          { return redirect()->back()->with('error', 'Position is not valid'); }
            if (empty($request->companyname))       { return redirect()->back()->with('error', 'Company name is not valid'); }
            if (empty($request->companyregnumber))  { return redirect()->back()->with('error', 'Company reg number is not valid'); }
            if (empty($request->incorporationtype)) { return redirect()->back()->with('error', 'Incorporation type is not valid'); }
        }
        $email    = User::where("email", $request->email)->first();
        $username = User::where("username", $request->username)->first();
        if ($email != null) { return redirect()->back()->with('error', 'Email already registered'); }
        if ($username != null) { return redirect()->back()->with('error', 'Username already taken'); }

        # save user
        $user_store            = new User();
        $user_store->firstname = $request->firstname;
        $user_store->lastname  = $request->lastname;
        $user_store->username  = $request->username;
        $user_store->email     = $request->email;
        $user_store->password  = Hash::make($request->password);
        $user_store->phone     = $request->intlphone;
        $user_store->status    = 1;
        if ($request->formtype == "individual") {
            $user_store->birthdate     = $request->birthdate;
            $user_store->occupation    = $request->occupation;
            $user_store->homeaddress   = $request->homeaddress;
            $user_store->addr2         = $request->addr2;
            $user_store->city          = $request->city;
            $user_store->stateprovince = $request->stateprovince;
            $user_store->zipcode       = $request->zipcode;
        } else {
            $user_store->position          = $request->position;
            $user_store->companyname       = $request->companyname;
            $user_store->companyregnumber  = $request->companyregnumber;
            $user_store->uk_eu_vat         = $request->uk_eu_vat ;
            $user_store->launchdate        = $request->launchdate;
            $user_store->incorporationtype = $request->incorporationtype;
            $user_store->type              = 1;
        }
        $user_store->save();

        // process user docs to /script/storage/app/docs/
        $docfileput = function($arr, $r, $id) {
            foreach ($arr as $fn) {
                if ($r->hasFile($fn) && $r->file($fn)->isValid()) {
                    $filename = $fn.".".$r->file($fn)->extension();
                    $path = Storage::putFileAs('docs/'.$id, $r->file($fn), $filename);
            }}};
        if ($request->formtype == "individual") { $docfileput(["doc1", "doc2"], $request, $user_store->id); }
        else { $docfileput(["doc1", "doc2", "doc3", "doc4", "doc5", "doc6", "doc7", "doc8"], $request, $user_store->id); }
        Auth::login($user_store);
        return redirect('/user/dashboard');
    }

    public function logout(Request $request) {
        Auth::logout();
        Session::flush();
        return redirect('/login');
    }

    public function phone_verification(Request $request) { 
        if ($request->isMethod('POST')) {
            if ($request->session()->get('phone2fa') != $request->code) {
                return redirect()->route('phone.verification.view')->with("error", "Code does not match!");
            } else {
                $user_store = User::findOrFail($request->session()->get('user_id') ?? Auth::user()->id);
                $user_store->phone_verified_at = date('Y-m-d H:i:s');
                $user_store->save();
                return redirect('/user/dashboard');
            }  
        } 
        return view('user.phone_verification');
    }

    public function phone_verification_resend(Request $request){
        $this->phone2fa_generate();
        return redirect()->route('phone.verification.view')->with("success", "Code sent successfully");
    }

    public function phone2fa_view(Request $request) {
        if (!$request->session()->get('phone2fa')) { $this->phone2fa_generate(); }
        return view('user.phone_verification');   
    }

    public function phone2fa_generate() {
        $phone2fa = rand(1000, 9999);
        Session::put('phone2fa', $phone2fa);
        $sid      = "ACa86ab439dadcb2cab37400ca1107c7f2";
        $token    = "79b5438499e69b32559fb0b972d7440f";
        $client   = new Client($sid, $token);
        $client->messages->create(
            Auth::user()->phone,
            array(
                'from' => "+19035688949",
                'body' => "MBI:COREBANK ".$phone2fa
            )
        );
    }
}

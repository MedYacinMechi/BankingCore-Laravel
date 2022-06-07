<?php

namespace App\Http\Controllers;

use App\Models\Option;
use App\Models\Term;
use App\Models\User;
use App\Models\Withdrawmethod;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Newsletter;
use App\Models\Language;
use App\Models\Termwithdraw;
use Artesaos\SEOTools\Facades\SEOTools;
use Artesaos\SEOTools\Facades\JsonLdMulti;
use Artesaos\SEOTools\Facades\SEOMeta;
use DB;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        // $this->middleware('auth');
    }

    
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        header("Location: /login");
        exit();
    }

    public function lang($code)
    {
        $lang = Language::where('name',$code)->first();
        \Session::put('lang_position',$lang->position);
    	\Session::put('locale',$code);

        return back();
    }

}

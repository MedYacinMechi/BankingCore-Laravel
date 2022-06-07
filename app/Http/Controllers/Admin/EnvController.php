<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use File;
use Illuminate\Support\Str;
class EnvController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if(!Auth()->user()->can('system.settings'))
        {
            abort(401);
        }    
        $countries= base_path('resources/lang/langlist.json');
        $countries= json_decode(file_get_contents($countries),true);

        return view('admin.settings.env',compact('countries'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
     
       $APP_NAME = Str::slug($request->APP_NAME);
       $PUSHER_APP_KEY = $request->PUSHER_APP_KEY;
       $PUSHER_APP_CLUSTER = $request->PUSHER_APP_CLUSTER;
$txt ="APP_NAME=".$APP_NAME."
APP_ENV=".$request->APP_ENV."
APP_KEY=base64:kZN2g9Tg6+mi1YNc+sSiZAO2ljlQBfLC3ByJLhLAUVc=
APP_DEBUG=".$request->APP_DEBUG."
APP_URL=".$request->APP_URL."
LOG_CHANNEL=".$request->LOG_CHANNEL."


DB_CONNECTION=".env("DB_CONNECTION")."
DB_HOST=".env("DB_HOST")."
DB_PORT=".env("DB_PORT")."
DB_DATABASE=".env("DB_DATABASE")."
DB_USERNAME=".env("DB_USERNAME")."
DB_PASSWORD=".env("DB_PASSWORD")."


BROADCAST_DRIVER=".$request->BROADCAST_DRIVER."
CACHE_DRIVER=".$request->CACHE_DRIVER."
QUEUE_CONNECTION=".$request->QUEUE_CONNECTION."
SESSION_DRIVER=".$request->SESSION_DRIVER."
SESSION_LIFETIME=".$request->SESSION_LIFETIME."

REDIS_HOST=".$request->REDIS_HOST."
REDIS_PASSWORD=".$request->REDIS_PASSWORD."
REDIS_PORT=".$request->REDIS_PORT."

QUEUE_MAIL=".$request->QUEUE_MAIL."

MAIL_MAILER=".$request->MAIL_MAILER."
MAIL_HOST=".$request->MAIL_HOST."
MAIL_PORT=".$request->MAIL_PORT."
MAIL_USERNAME=".$request->MAIL_USERNAME."
MAIL_PASSWORD=".$request->MAIL_PASSWORD."
MAIL_ENCRYPTION=".$request->MAIL_ENCRYPTION."
MAIL_FROM_ADDRESS=".$request->MAIL_FROM_ADDRESS."
MAIL_TO=".$request->MAIL_TO."
MAIL_FROM_NAME=".Str::slug($request->MAIL_FROM_NAME)."

MAILCHIMP_APIKEY=".$request->MAILCHIMP_APIKEY."
MAILCHIMP_LIST_ID=".$request->MAILCHIMP_LIST_ID."

TIMEZONE=".$request->TIMEZONE.""."
DEFAULT_LANG=".$request->DEFAULT_LANG."\n
";
       File::put(base_path('.env'),$txt);
       return response()->json("System Updated");
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}

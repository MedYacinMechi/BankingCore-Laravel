<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Str;
use File;
use Artisan;

class AdminController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (Auth()->user()->can('admin.list')) {
            $users = User::where('role_id',1)->where('id','!=',1)->latest()->get();
            return view('admin.admin.index', compact('users'));
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if (Auth()->user()->can('admin.create')) {
            $roles  = Role::all();
            return view('admin.admin.create', compact('roles'));
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // Validation Data
        $request->validate([
            'name' => 'required|max:50',
            'roles' => 'required',
            'email' => 'required|max:100|email|unique:users',
            'phone' => 'required|max:20|unique:users',
            'password' => 'required|min:6|confirmed',
        ]);

        // Create New User
        $user = new User();
        $user->name = $request->name;
        $user->email = $request->email;
        $user->phone = $request->phone;
        $user->role_id = 1;
        $user->password = Hash::make($request->password);
        $user->save();

        if ($request->roles) {
            $user->assignRole($request->roles);
        }


        return response()->json(['User has been created !!']);
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
        if (Auth()->user()->can('admin.edit')) {
            $user = User::find($id);
            $roles  = Role::all();
            return view('admin.admin.edit', compact('user', 'roles'));
        }
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
        // Create New User
        $user = User::find($id);

        // Validation Data
        $request->validate([
            'name' => 'required|max:50',
            'email' => 'required|max:100|email|unique:users,email,' . $id,
            'phone' => 'required|max:20|unique:users,phone,' . $id,
            'password' => 'nullable|min:6|confirmed',
        ]);


        $user->name = $request->name;
        $user->email = $request->email;
        $user->phone = $request->phone;
        $user->status = $request->status;
        if ($request->password) {
            $user->password = Hash::make($request->password);
        }
        $user->save();

        $user->roles()->detach();
        if ($request->roles) {
            $user->assignRole($request->roles);
        }


        return response()->json(['User has been updated !!']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {

        if (Auth()->user()->can('admin.delete')) {
            
                if ($request->status == 'delete') {
                    if ($request->ids) {
                        foreach ($request->ids as $id) {
                            User::destroy($id);
                        }
                    }
                }
                else{
                   
                    if ($request->ids) {
                        foreach ($request->ids as $id) {
                            $post = User::find($id);
                            $post->status = $request->status;
                            $post->save();
                        }
                    }
                }
            
        }

        return response()->json('Success');
    }

    public function install_info()
    {
        try {
            DB::select('SHOW TABLES');
            return redirect('/404');         
        } catch (\Exception $e) { return view('install.info'); }
    }

    public function install_send(Request $request)
    {

        $APP_NAME = Str::slug($request->app_name);
        $txt ="APP_NAME=".$APP_NAME."
            APP_ENV=local
            APP_KEY=base64:kZN2g9Tg6+mi1YNc+sSiZAO2ljlQBfLC3ByJLhLAUVc=
            APP_DEBUG=true
            APP_URL=".$request->app_url."
            LOG_CHANNEL=stack\n
            DB_CONNECTION=mysql
            DB_HOST=".$request->db_host."
            DB_PORT=3306
            DB_DATABASE=".$request->db_name."
            DB_USERNAME=".$request->db_user."
            DB_PASSWORD=".$request->db_pass."\n
            BROADCAST_DRIVER=log
            CACHE_DRIVER=file
            QUEUE_CONNECTION=database
            SESSION_DRIVER=file
            SESSION_LIFETIME=120\n
            REDIS_HOST=127.0.0.1
            REDIS_PASSWORD=null
            REDIS_PORT=6379\n
            QUEUE_MAIL=off\n
            MAIL_MAILER=smtp
            MAIL_HOST=smtp.mailtrap.io
            MAIL_PORT=2525
            MAIL_USERNAME=
            MAIL_PASSWORD=
            MAIL_ENCRYPTION=tls
            MAIL_FROM_ADDRESS=
            MAIL_TO=
            MAIL_FROM_NAME=\n

            MAILCHIMP_APIKEY=
            MAILCHIMP_LIST_ID=

            TIMEZONE=UTC
            DEFAULT_LANG=en\n
                   ";
        File::put(base_path('.env'),$txt);
        return "Sending Credentials";
    }

    public function install_migrate()
    {
        ini_set('max_execution_time', '0');
        \Artisan::call('migrate:fresh');
        return "Demo Importing";
    }

    public function install_seed()
    {
        ini_set('max_execution_time', '0');
        \Artisan::call('db:seed');
        return "Congratulations! Your site is ready";
    }

    public function install_install()
    {
        try {
            DB::select('SHOW TABLES');
            return redirect('/404');         
        } catch (\Exception $e) { }

        try {
            DB::connection()->getPdo();
            if (DB::connection()->getDatabaseName()) {
                return abort(404);
            } else {
                $phpversion = phpversion();
                $mbstring = extension_loaded('mbstring');
                $bcmath = extension_loaded('bcmath');
                $ctype = extension_loaded('ctype');
                $json = extension_loaded('json');
                $openssl = extension_loaded('openssl');
                $pdo = extension_loaded('pdo');
                $tokenizer = extension_loaded('tokenizer');
                $xml = extension_loaded('xml');

                $info = [
                    'phpversion' => $phpversion,
                    'mbstring' => $mbstring,
                    'bcmath' => $bcmath,
                    'ctype' => $ctype,
                    'json' => $json,
                    'openssl' => $openssl,
                    'pdo' => $pdo,
                    'tokenizer' => $tokenizer,
                    'xml' => $xml,
                ];
                return view('install.requirments', compact('info'));
            }
        } catch (\Exception $e) {
            $phpversion = phpversion();
            $mbstring = extension_loaded('mbstring');
            $bcmath = extension_loaded('bcmath');
            $ctype = extension_loaded('ctype');
            $json = extension_loaded('json');
            $openssl = extension_loaded('openssl');
            $pdo = extension_loaded('pdo');
            $tokenizer = extension_loaded('tokenizer');
            $xml = extension_loaded('xml');

            $info = [
                'phpversion' => $phpversion,
                'mbstring' => $mbstring,
                'bcmath' => $bcmath,
                'ctype' => $ctype,
                'json' => $json,
                'openssl' => $openssl,
                'pdo' => $pdo,
                'tokenizer' => $tokenizer,
                'xml' => $xml,
            ];
            return view('install.requirments', compact('info'));
        }
    }

    public function install_check()
    {
        try {
            DB::connection()->getPdo();
            if(DB::connection()->getDatabaseName()) {
                return "Database Installing";
            } else {return false; }
        } catch (\Exception $e) { return false; }
        
    }
}

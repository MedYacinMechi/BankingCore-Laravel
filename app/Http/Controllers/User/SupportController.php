<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Support;
use App\Models\Supportmeta;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Notifications\Usertransctionnotification;

class SupportController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {  
        $supports = Support::with('meta')->where('user_id',Auth::id())->latest()->paginate();
        return view('user.support.index', compact('supports'))->with('i',1);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('user.support.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'title'       => 'required|max:100',
            'comment' => 'required|max:250',
        ]);

        $support = new Support();
        $support->title = $request->title;
        $support->category = $request->categorie;
        $support->ticket_no = 0;
        $support->user_id = Auth::id();
        $support->save();

        $supportmeta = new Supportmeta();
        $supportmeta->support_id = $support->id;
        $supportmeta->comment = $request->comment;
        $supportmeta->save();
        $support = Support::findorFail($support->id);
        $support->ticket_no=$support->id;
        $support->save();
         
        return response()->json('Message send successfully!'); 
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $support = Support::with('user')->with('meta')->where('user_id',Auth::id())->findorFail($id);
      
        return view('user.support.view', compact('support'));
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
        $request->validate([
            'comment' => 'required',
        ]);

        $count = Support::where('id', $id)->where('user_id', Auth::id())->count();

        if ($count == 0) {
            return response()->json('Not authorized!'); 
        }
     
        
        $supportmeta = new Supportmeta();
        $supportmeta->support_id = $id; 
        $supportmeta->comment = $request->comment;
        $supportmeta->type = 1; //for admin
        $supportmeta->save();

        return response()->json('Reply send successfully!'); 
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


    
    public function generateAccNo(){
        $rend = rand(10000000, 99999999). rand(10000000, 99999999);
        $check = User::where('account_number', $rend)->first();

        if($check == true){
            $rend = $this->generateAccNo();
        }
        return $rend;
    }

}

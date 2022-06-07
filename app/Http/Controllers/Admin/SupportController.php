<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Support;
use App\Models\Supportmeta;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;
use App\Notifications\Usertransctionnotification;
use App\Models\User;
class SupportController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (!Auth()->user()->can('support.index')) {
            return abort(401);
        }
        //todo: paginate this
        $supports    = Support::all()->unique('category');
        $allsupports = Support::all();
        foreach ($supports as $key => $support) {
            $support["unread"] = Supportmeta::where("category", $support->category)
            ->where("type", 1)->where("seen", 0)->count();
        }
        return view('admin.support.categories', compact('supports', 'allsupports',));
    }
    
    public function getCategory($category) {
        return view('admin.support.index', array('category' => $category));
    }

    public function getCategoryRecent(Request $request) {  
        $supports = Support::with(['meta'])->where('category', $request->category)->latest()->paginate(5);
        return $this->ticketsPreview($supports);
    }

    public function searchInCategory(Request $request) {
        $supports = Support::with(['meta'])->where('category', $request->category)
        ->where('title', 'like', '%' . $request->keyword . '%')
        ->latest()->paginate(5);
        return $this->ticketsPreview($supports);
    }

    private function ticketsPreview($supports) {
        foreach ($supports as $k => $support) {
            $support["avatar"]  = $this->avatarfunc($support->user);
            $comment = $support->meta->sortBy('created_date')->last();
            if ($comment != null) { $comment = $comment["comment"]; }
            $preview = substr($comment, 0, 90);
            $suffix  = strlen($comment) > 90 ? "..." : "";
            $support["lastmsg"] = $preview.$suffix;
            $support["unread"]  = $support->meta->where('type', 1)->where('seen', 0)->count();
        }
        $result = json_decode(json_encode($supports));
        foreach ($result->data as $support) { 
            $support->user = null; 
            $support->meta = null; 
        }
        return json_encode($result);
    }

    public function avatarfunc($user) {
        $data = [];
        $data["name"]     = null; #path to avatar
        $data['initials'] = substr($user->firstname, 0, 1).substr($user->lastname, 0, 1);
        if (empty($user->firstname)) { $data['initials'] = "A"; }
        return $data;
    }

    public function getSupportData(Request $request) {
        $support           = Support::with('user')->with('meta')->where('id', $request->id)->first();
        $data              = [];
        $data["firstname"] = $support->user->firstname;
        $data["lastname"]  = $support->user->lastname;
        $data["lastseen"]  = $support->user->last_active;
        $data["avatar"]    = $this->avatarfunc($support->user);
        $data['msgs']      = [];
        $data["sid"]       = $request->id;
        $i = 0;
        foreach ($support->meta->sortBy("created_at")->take(25) as $k => $meta) {
            $data['msgs'][$i]['name']       = $meta->type == 0 ? Auth::user()->name : $support->user->name;
            $data['msgs'][$i]['avatar']     = $this->avatarfunc($meta->type == 0 ? Auth::user() : $support->user);
            $data['msgs'][$i]['date']       = $meta->created_at;
            $data['msgs'][$i]['comment']    = $meta->comment;
            $data['msgs'][$i]['type']       = $meta->type;
            $data['msgs'][$i]['msgtype']    = $meta->msgtype;
            $data['msgs'][$i]['attachment'] = $meta->attachment;
            $data['msgs'][$i]['origattchname']  = $meta->origattchname;
            $data['msgs'][$i]['attachmentsize'] = $meta->attachmentsize;
            $i++;
        }
        return json_encode($data);
    }

    public function supportStatus(Request $request){

        $support = Support::findOrFail($request->id);
        $support->status = $request->status;

        $support->save();
        // Session::put('message','Status changed successfully!');
        return json_encode(1);
    }

    public function newmessage(Request $request)
    {
        //TODO further validation required
        $request->validate([
            'type' => 'required',
            'sid'  => 'required'
        ]);

        $supportmeta = new Supportmeta();
        $supportmeta->support_id = $request->sid;
        $supportmeta->type       = 0; // msg origin 0:admin 1:user
        $data                    = [];
        $data["msg"]             = $supportmeta;
        $data["avatar"]          = $this->avatarfunc(Auth::user());
        switch ($request->type) {
            case 0:
                $supportmeta->comment = $request->text;
                $supportmeta->msgtype = 0; // 0:text 1:img 2:file
                $supportmeta->save();
                return json_encode(array("success" => true, "error" => null, "meta" => $data));
            case 1:
                if (!$request->hasFile('file') || !$request->file("file")->isValid()) { 
                    return json_encode(array("success" => false, 
                        "error" => "Valid Attachment Required"));
                }
                $ext = $request->file("file")->extension();
                $filename = Auth::user()->account_number.rand().time().".".$ext;
                $path = Storage::putFileAs('support-attachements/', $request->file("file"), $filename);
                $supportmeta->attachment     = $path;
                $supportmeta->origattchname  = $request->file("file")->getClientOriginalName();
                $supportmeta->attachmentsize = $request->file("file")->getSize();
                $imgexts = array("jpg", "jpeg", "png", "bmp", "gif");
                $supportmeta->msgtype    = in_array($ext, $imgexts) ? 1 : 2; // 0:text 1:img 2:file
                $supportmeta->save();
                /*$user=User::find('2');
                $ex= 0;
              // dd($user->unreadNotifications->isEmpty());
               foreach ($user->unreadNotifications as $notification) {
                  if($notification->data['type']=="support_ticket"){
                   $ex=1; 
                   
                  }
                        }
   
               if(($user->unreadNotifications->isEmpty()) || ($ex==0)) 
               {  $post=[
                'type'=>'support_ticket',
                'message'=>'you received a ticket'
                    ];
              $user->notify(new Usertransctionnotification($post));}*/
                return json_encode(array("success" => true, "error" => null, "meta" => $data));
                break;
        }
    }

    public function markseen(Request $request) {
        $support = Support::with(['meta'])->where('id', $request->id)->first();
        foreach ($support->meta as $key => $meta) {
            $meta->seen = 1;
            $meta->save();
        }
        return json_encode(1);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        
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
        if (!Auth()->user()->can('support.edit')) {
            return abort(401);
         }
        $support = Support::with(['meta'])->where('id', $id)->get();
        return view('admin.support.edit' , compact('support','id'));
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

        $support = Support::findOrFail($id);
        $support->status = 1;  
        $support->save();
        
        $supportmeta = new Supportmeta();
        $supportmeta->support_id = $id; 
        $supportmeta->comment = $request->comment;
        $supportmeta->type = 0; //for admin
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
        if (!Auth()->user()->can('support.delete')) {
            return abort(401);
         }
        $support = Support::findOrFail($id);
        $supportmeta = Supportmeta::where('support_id',$id);
        $support->delete();
        $supportmeta->delete();
        return redirect()->back()->with('success', 'Successfully Deleted!');   
    }
}

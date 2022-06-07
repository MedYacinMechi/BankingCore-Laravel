<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use App\Models\WireData;
use App\Models\Fees;
use App\Models\User;
use App\Models\SystemRequest;
use Illuminate\Http\Request;
use App\Notifications\Usertransctionnotification;


class SystemRequestsController extends Controller
{

	public function index() {
		$requests = SystemRequest::latest()->paginate(50);
		return view('admin.requests.index', compact('requests'));
	}

	public function view($id) {
		# todo: other types of request
		$request = SystemRequest::where("id", $id)->first();
		$transaction = null;
		switch ($request->type) {
			case 0:
				$transaction = Transaction::where("id", $request->ref_id)->first();
				break;
		}
		return view('admin.requests.view', compact('request', 'transaction'));
	}

	public function setstatus(Request $request) {
		# todo: other types of request
		$message="executed";
		if($request->status == 0){$message="pending";}
		if($request->status == 2){$message="canceled";}
		if ($request->status != 1 && $request->status != 2) {
			return redirect()->back()->with('message', 'Invalid Status'); }
		if ($request->type == 0) {
			$sysreq = SystemRequest::findOrfail($request->id);
			$tx     = Transaction::findOrfail($sysreq->ref_id);
			$fee    = Fees::where("refid", $tx->id)->first();
			if ($fee == null) { return abort(404); }
			$sysreq->status = $request->status;
			$tx->status     = $request->status;
			$fee->status    = $request->status;
			if ($tx->wireid != null) {
				$wire = WireData::findOrfail($tx->wireid);
				$wire->status = $request->status;
				$wire->save(); }
			$sysreq->save();
			$tx->save();
			$fee->save();
			/*$user=User::find($sysreq->user_id);
			$post=[
                'type'=>'transaction ',
                'message'=>'your transaction'.$sysreq->subject.'for number id'.$tx->id.' is '.$message
                    ];
              $user->notify(new Usertransctionnotification($post));*/
			return redirect()->back()->with('success', 'Status Updated');
		}
		return redirect()->back()->with('error', 'Not yet implemented');
	}
}
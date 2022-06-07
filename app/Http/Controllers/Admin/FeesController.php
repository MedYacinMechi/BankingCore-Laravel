<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\TransactionFees;
use App\Models\Currency;
use Illuminate\Http\Request;

class FeesController extends Controller
{

	public function index(Request $request) {
		if ($request->currency != null && is_numeric($request->currency)) {
			$fees = TransactionFees::where("currencyid", $request->currency)->paginate(25);
		} else { $fees = TransactionFees::paginate(6); }
		$currencies = Currency::get();
		return view("admin.fees.index", compact("fees", "currencies"));
	}

	public function edit(Request $request, $id) {
		$fee = TransactionFees::findOrFail($id);
		$mcurrency  = $fee->currency;
		$currencies = Currency::get();
		return view("admin.fees.edit", compact("fee", "currencies"));
	}

}
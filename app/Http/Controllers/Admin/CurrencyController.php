<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;


use App\Models\Currency;

class CurrencyController extends Controller
{
    public function index() {
        if (!Auth()->user()->can('currency.index')) {
            return abort(401);
        }
        $currencies = currency::paginate(10);
        return view('admin.currency.index', compact('currencies'));
    }

    public function create() {
        if (!Auth()->user()->can('currency.create')) {
            return abort(401);
        }
        $path = base_path('resources/currency_list/currency_liste.json');
        $currencylist = json_decode(file_get_contents($path), true);
        return view('admin.currency.create', compact('currencylist'));
    }

    public function store(Request $request) {
        $request->validate(['title'   => 'required']);
        $path = base_path('resources/currency_list/currency_with_symbol.json');
        $currencylist = json_decode(file_get_contents($path), true);
       
        //currency store
        $currency = new Currency();
        $currency->fullname = $request->title;
        $currency->ISOcode  = $request->currency_name;
        $currency->status   = $request->status;
        $currency->symbol   = $currencylist[$request->currency_name]["symbol"];

        if ($request->default != null) {
            $currency_def = Currency::where('currency_default', 1)->first(); 
            if($currency_def != null){
               $currency_def->currency_default = 0;
               $currency_def->save();}
               $currency->currency_default = 1; 
            } else {
                $currency->currency_default = 0;
            }
        $currency->save();
        return redirect()->back()->with("success", "Currency ".$currency->ISOcode." Added Successfully");
    }

    public function edit($id) {
        if (!Auth()->user()->can('currency.edit')) { return abort(401); }
        $currency   = Currency::findOrFail($id);
        $currencies = Currency::where("id", '!=', $id)->get();
        $rates = Storage::disk('public')->get('currency_rates.json');
        $rates = json_decode($rates, true);
        if (!array_key_exists($currency->ISOcode, $rates)) 
            { return redirect()->back()->with("error", "Currency not found in rates schema"); }
        $xrates = [];
        foreach ($currencies as $cur) {
            $manuallyset = false;
            if (!array_key_exists($cur->ISOcode, $rates[$currency->ISOcode])) { continue; }
            if (array_key_exists($cur->ISOcode.".m", $rates[$currency->ISOcode])
                && $rates[$currency->ISOcode][$cur->ISOcode.".m"] == true) {
                $manuallyset = true;
            }
            $xrates[$cur->ISOcode] = array($rates[$currency->ISOcode][$cur->ISOcode], $manuallyset);
        }
        return view('admin.currency.edit', compact('currency', 'currencies', 'xrates'));
    }

    public function update(Request $request, $id) {
        if (!Auth()->user()->can('currency.edit')) { return abort(401); }
        if (empty($request->fullname)) { return redirect()->back()->with("error", "Invalid fullname"); }
        if (empty($request->isocode))  { return redirect()->back()->with("error", "Invalid ISO code"); }
        if (!is_numeric($request->precision)) { return redirect()->back()->with("error", "Invalid precision"); }
        if ($request->status != 0 && $request->status != 1) { return redirect()->back()->with("error", "Invalid status"); }
        $mcurrency = Currency::where("id", $id)->first();
        if ($mcurrency == null) { return redirect()->back()->with("error", "Currency not found"); }
        $rates = Storage::disk('public')->get('currency_rates.json');
        $rates = json_decode($rates, true);
        if (!array_key_exists($mcurrency->ISOcode, $rates)) 
            { return redirect()->back()->with("error", "Currency not found in rates schema"); }
        $currencies = Currency::where("id", '!=', $id)->get();
        foreach ($currencies as $currency) {
            if (!$request->has($currency->ISOcode)) { continue; }
            $manualrate = $request->input($currency->ISOcode);
            if (!empty($manualrate) && !is_numeric($manualrate)) 
                { return redirect()->back()->with("error", "Invalid rate for $currency->ISOcode"); }
            if (empty($manualrate)) {
                $rates[$mcurrency->ISOcode][$currency->ISOcode.".m"] = false;
                continue; }
            if ($mcurrency->ISOcode == $currency->ISOcode) { continue; }
            if ($rates[$mcurrency->ISOcode][$currency->ISOcode] == $manualrate) { continue; }
            $rates[$mcurrency->ISOcode][$currency->ISOcode] = floatval($manualrate);
            $rates[$mcurrency->ISOcode][$currency->ISOcode.".m"] = true;
        }
        $scheme = json_encode($rates, JSON_PRETTY_PRINT);
        if ($mcurrency->ISOcode != $request->isocode) {
            $scheme = str_replace('"'.$mcurrency->ISOcode.'"',   '"'.$request->isocode.'"',   $scheme);
            $scheme = str_replace('"'.$mcurrency->ISOcode.'.m"', '"'.$request->isocode.'.m"', $scheme); }
        $mcurrency->fullname = $request->fullname;
        $mcurrency->ISOcode  = $request->isocode;
        $mcurrency->symbol   = $request->symbol;
        $mcurrency->status   = $request->status;
        $mcurrency->save();
        Storage::disk('public')->put('currency_rates.json', $scheme);
        return redirect()->back()->with("success", "Currency ".$mcurrency->ISOcode." Updated Successfully");
    }

    public function destroy($id) {
        if (!Auth()->user()->can('currency.delete')) { return abort(401); }
        $currency = Currency::findOrFail($id);
        $currency->delete();
        return redirect()->back()->with('success', 'Successfully Deleted!');   
    }

    public function setstatus(Request $request) {
        #todo: change to disable role
        #if (!Auth()->user()->can('currency.delete')) { return abort(401); }
        $currency = Currency::findOrFail($request->id);
        $currency->status = $request->status == 1 ? 1 : 0;
        $currency->save();
        $newstatus = ($currency->status == 1 ? "enabled" : "disabled");
        return redirect()->back()->with("success", "Currency ".$currency->ISOcode." has been $newstatus");
    }
}
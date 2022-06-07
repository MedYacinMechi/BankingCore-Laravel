<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Term;
use App\Models\Termmeta;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
class ServiceController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (!Auth()->user()->can('service.index')) {
            return abort(401);
         }
        
        $services = Term::where('type','services')->with('serviceMeta')->latest()->paginate(10);
        return view('admin.service.index', compact('services'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if (!Auth()->user()->can('service.create')) {
            return abort(401);
         }
        return view('admin.service.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        
        // Validate
        $request->validate([
            'title' => 'required',
            'image' => 'image|max:3062',
            'status'=> 'required',
        ]);
        $image_path = $image_name = '';
        // Image Check
        if ($request->hasFile('image')) {
            $image      = $request->file('image');
            $image_name = hexdec(uniqid()) . '.' . $image->getClientOriginalExtension();
            $image_path = 'uploads/' . date('y/m/');
            $image->move($image_path, $image_name); 
        }

        // Data
        $data = [
            'description' => $request->description,
            'short_description' => $request->short_description,
            'image'   => $image_path.$image_name,
        ];

        // Page Data Store
        $services           = new Term();
        $services->title    = $request->title;
        $services->slug     = Str::slug($request->title);
        $services->type     = 'services';
        $services->status   = $request->status;
        $services->featured = 1;
        $services->save();

        // page Meta data store
        $services_meta          = new Termmeta();
        $services_meta->term_id = $services->id;
        $services_meta->key     = 'services';
        $services_meta->value   = json_encode($data);
        $services_meta->save();

        return response()->json('Services Added Successfully');
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
        if (!Auth()->user()->can('service.edit')) {
            return abort(401);
         }

        $service = Term::findOrFail($id);
        return view('admin.service.edit', compact('service'));
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
         $services_meta          = Termmeta::where('term_id', $id)->first();
         $meta=json_decode($services_meta->value);
        // Image Check
        if ($request->hasFile('image')) {
            $image      = $request->file('image');
            $image_name = hexdec(uniqid()) . '.' . $image->getClientOriginalExtension();
            $image_path = 'uploads/' . date('y/m/');
            $image->move($image_path, $image_name);
            $img=$image_path.$image_name;
            if(!empty($meta->image)){
              if(file_exists($meta->image)){
                unlink($meta->image);
              }  
            }
            
        }
        else{
            $img=$meta->image;
        }

        // Data
        $data = [
            'description' => $request->description,
            'short_description' => $request->short_description,
            'image'   => $img,
        ];

        // Page Data Store
        $services           = Term::findOrFail($id);
        $services->title    = $request->title;
        $services->slug     = Str::slug($request->title);
        $services->type     = 'services';
        $services->status   = $request->status;
        $services->featured = 1;
        $services->save();

        // page Meta data store
       
        $services_meta->term_id = $services->id;
        $services_meta->key     = 'services';
        $services_meta->value   = json_encode($data);
        $services_meta->save();

        return response()->json('How it work updated Successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if (!Auth()->user()->can('service.destroy')) {
            return abort(401);
         }
        $services = Term::findOrFail($id);
        $services->delete();
        return redirect()->back()->with('success', 'Successfully Deleted'); 
    }
}

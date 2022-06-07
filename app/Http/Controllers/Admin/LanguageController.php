<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Language;
use Illuminate\Support\Facades\File;

class LanguageController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (!Auth()->user()->can('language.index')) {
            return abort(401);
        }
        $languages = Language::latest()->paginate(10);
        return view('admin.language.index', compact('languages'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if (!Auth()->user()->can('language.create')) {
           return abort(401);
        }
        $path = base_path('resources/lang/langlist.json');
        $langlist = json_decode(file_get_contents($path), true); 
        return view('admin.language.create', compact('langlist'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // if (!Auth()->user()->can('language.store')) {
        //     return abort(401);
        //  }
        $request->validate([
            'name'  => 'unique:languages',
        ]);

        $path = base_path('resources/lang/langlist.json'); 
        $langlist = json_decode(file_get_contents($path), true); 

        foreach ($langlist as $item) {
            if ($item['code'] == $request->name) {
                $data = array(
                    'name' => $item['code'],
                    'position' => $request->position,
                    'data' => $item['name'],
                    'created_at' => now(),
                    'updated_at' => now(),
                );
            }
        }
        $language = new Language();
        $language->insert($data);

        //read en file
        $existFilePath = base_path('resources/lang/en.json'); 

        //read exist file data
        $existData = json_decode(file_get_contents($existFilePath), true); 

        //write data
        file_put_contents(base_path('resources/lang/'.$request->name.'.json'), json_encode($existData, JSON_PRETTY_PRINT));
        
        return response()->json('Language Successfully Added!');
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
        $lang = Language::find($id);

        if(!$lang){
            return abort(404);
        }

        $lang_file = file_get_contents(resource_path('lang/'.$lang->name.'.json'));
        $langs = json_decode($lang_file,true);

        return view('admin.language.edit',compact('langs','id'));
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
        $language = Language::find($id);

        $lang_file = file_get_contents(resource_path('lang/'.$language->name.'.json'));
        $langs = json_decode($lang_file,true);


        foreach($langs as $key=>$lang)
        {
            if($key == $request->key)
            {
                $langs[$key] = $request->value;
            }
        }

        file_put_contents(resource_path('lang/'.$language->name.'.json'),json_encode($langs,JSON_PRETTY_PRINT));

        return response()->json('Data Updated');
    }

    public function lang_set(Request $request)
    {
        
        if($request->status)
        {
            if($request->id)
            {
                $lang = language::all();
                foreach($lang as $data)
                {
                    $data->status = 0;
                    $data->save();
                }
                

                foreach($request->id as $value)
                {
                    $language = Language::find($value);
                    $language->status = 1;
                    $language->save();
                } 

                return response()->json('Language Successfully Activated');
            }else{
                $msg['errors']['error'] = "Language Field is required";
                return response()->json($msg,401);
            }
            
        }else{
            $msg['errors']['error'] = "Active language field is required.";
            return response()->json($msg,401);
        }
        
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if (!Auth()->user()->can('language.delete')) {
            return abort(401);
         }
        $language = Language::findOrFail($id);
        $path = base_path('resources/lang/'.$language->name.'.json');   
        if (File::exists($path)) {
            File::delete($path);
        }
        $language->delete();
        return redirect()->back()->with('success', 'Successfully Deleted');
    }
}

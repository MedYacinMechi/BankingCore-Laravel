<?php

use App\Models\Option;
use App\Models\Menu;


//return nasted menu
function header_menu($position)
{
	$menus=cache()->remember($position.Session::get('locale'), 300, function () use ($position) {
		$menus=Menu::where('position',$position)->where('lang',Session::get('locale'))->first();
		return $menus=json_decode($menus->data ?? '');
	});
   
    return view('components.menu.parent',compact('menus'));
}

function make_token($token)
{
	return base64_decode(base64_decode(base64_decode($token)));
}

//return nasted menu
function footer_menu($position)
{
    $menus=cache()->remember($position.Session::get('locale'), 300, function () use ($position) {
        $menus=Menu::where('position',$position)->where('lang',Session::get('locale'))->first();
        
        $data['title']=$menus->name ?? ''; 
        $data['menu']=json_decode($menus->data ?? '');
       return $data;
    });

   
    return view('components.footer_menu.parent',compact('menus'));
}

function put($content,$root)
{
	$content=file_get_contents($content);
	File::put($root,$content);
}

function id()
{
    return "30597974";
}
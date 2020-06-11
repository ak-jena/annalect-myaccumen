<?php

namespace App\Http\Controllers\Debug;

use App\Http\Controllers\Controller;
use Cache;
use Illuminate\Http\Request;


class DebugController extends Controller {
    
    public function index(){
        
        \View::share('breadcrumbs', [
            ['url' => '/debug','name' => 'Debug & Maintainance'],
        ]);           
    	return view('debug.index');
    }
    
    public function error403(){
        abort(403);
    }

    public function error500(){
        abort(500);
    } 

    public function error503(){
        abort(503);
    }     

    public function clearCache(){
    	Cache::flush();
    	return \Redirect::back()->with('success', 'Application cache has just been cleared!');
    }
    
    public function showIdentity(){
        
        \View::share('breadcrumbs', [
            ['url' => '/debug','name' => 'Debug & Maintainance'],
        ]);
        $user = \Auth::user();
    	return view('debug.identity', ['user' => $user]);
    }

    public function showSession(Request $request){
        
        \View::share('breadcrumbs', [
            ['url' => '/debug','name' => 'Debug & Maintainance'],
        ]);
        
        $data = $request->session()->all();
        
    	return view('debug.session', ['data' => $data]);
    }

    public function showPath(){
        
        \View::share('breadcrumbs', [
            ['url' => '/debug','name' => 'Debug & Maintainance'],
        ]);           
    	return view('debug.path');
    }

    public function action1(){

    }
    
    public function action2(){
        
        return view('debug.action2');
    }

    public function action3(){

    }    
    
}
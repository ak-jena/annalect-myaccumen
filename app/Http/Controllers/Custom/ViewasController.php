<?php

namespace App\Http\Controllers\Custom;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Input;
use DB;
use Auth;
use Baselib;
use Alert;

class ViewasController extends Controller
{
    function __construct() {
        $this->init();
    }

    private function init(){
        $this->middleware(function ($request, $next) {
            if(!Baselib::hasViewAs()){
                abort(403, 'Access denied');
            }
            return $next($request);
        });
    }

    public function setViewAs(Request $request){

        $role_id = Input::get('viewas');
        if(!empty($role_id)){

            $role = DB::table('roles')->where('id', $role_id)->first();

            if($role_id != Auth::user()->role_id){
                $request->session()->put('viewas_role_id', $role_id);
                //check in case of already viewed-as
                if(!\Session::has('real_user_role_id')){
                    Baselib::retainRealRole();
                }
                $request->session()->put('user_role_id', $role->id);
                $request->session()->put('user_role_name', $role->name);
                Alert::warning('You are now viewing as: <span class="text-bold text-olive">'.$role->name.'</span>', 'Role swapped')->persistent('OK')->html(true);
                return redirect()->back();
            }
            else{
                return redirect()->back();
            }

        }
        else{
            $request->session()->forget('viewas_role_id');
            Baselib::revertRealRole();
            Alert::info('You are now reverted back to your real role', 'Role reverted')->persistent('OK');
            return redirect()->back();
        }
    }
}
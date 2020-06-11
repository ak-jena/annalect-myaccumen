<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;

class LockScreenController extends Controller
{
    public function get(){
    
        // Only allow lock if user is logged in
        if(\Auth::check()){
            \Session::put('locked', true);
            return view('auth.lockscreen');
        }
        
        return redirect('/login');
    }

    public function post()
    {
        // if user in not logged in 
        if(!\Auth::check()){
            return redirect('/login');
        }    

        $password = \Input::get('password');
        
        if(\Hash::check($password, \Auth::user()->password)){
            \Session::forget('locked');
            alert()->success('Your screen has been unlocked', 'Welcome back, '.\Baselib::getRealUserFirstName().'!')->autoclose(3500);
            return redirect('/');
        }
        else{
            return redirect('auth/lock');
        }
    }
}
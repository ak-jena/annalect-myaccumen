<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\User;
use Hash;

class ProfileController extends Controller
{
    public function index()
    {
        \View::share('breadcrumbs', [
            ['name' => 'Profile']
        ]);        
        $user = User::findOrFail(\Auth::user()->id);
        return view('profile.index')->withUser($user);
    }

    public function update(Request $request)
    {
        $user = User::findOrFail(\Auth::user()->id);
        
        $this->validate($request, [
            'newPassword' => 'required|min:6',
        ]); 

        $input = $request->all();
        
        if($input['newPassword']==="********"){
            return \Redirect::back();
        }           
        
        if($input['newPassword']!=="********"){
            $input['password'] = Hash::make($input['newPassword']);
        }        

        $user->fill($input)->save();

        return \Redirect::back()->with('success', 'Your profile has been successfully updated!');       
    }
    
}
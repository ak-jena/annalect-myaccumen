<?php

namespace App\Http\Controllers;

use App\Agency;
use App\Role;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Collection;
use Yajra\Datatables\Datatables;
use App\User;
use DB;
use Hash;
use Baselib;
use Mail;

class UserController extends Controller
{

    /**
     * Ajax load users
     */
    public function indexAjaxData()
    {

        $users_query = DB::table('users')
            ->join('roles', 'users.role_id', '=', 'roles.id')
            ->select('users.*', 'roles.name as role_name');

        // if agency user then limit to users within logged in users agency
        if(Baselib::isAgencyUser()){
            $logged_in_user = \Baselib::getUser(\Auth::user()->id);
            $agency_ids     = $logged_in_user->agencies()->pluck('id')->all();

            $users_query->join('agencies_users', 'users.id', '=', 'agencies_users.user_id')
                ->whereIn('agencies_users.agency_id',$agency_ids)
                ->where('users.role_id', '=', Role::AGENCY_USER);
        }

        $users = $users_query->get();

        return Datatables::of($users)
                ->editColumn('blocked', function($user){
                    if($user->blocked==1){
                        return "<span class='fa fa-ban text-red' rel='popover' data-trigger='hover' data-container='body' data-placement='top' data-original-title='Account blocked' data-content='This user is currently blocked'></span>";
                    }
                    else{
                        return "";
                    }
                })
                ->editColumn('name', function($user){
                    return "<span rel='popover' data-trigger='hover' data-container='body' data-placement='top' data-original-title='' data-content=''><a href='". url("user/".$user->id."/edit")."'>".$user->name."</a></span>";
                })
                ->editColumn('email', function($user){
                    return "<a href='mailto:". $user->email ."'>".$user->email."</a>";
                })                   
                ->editColumn('last_login', function($user){
                    if(isset($user->last_login) && !empty($user->last_login)){
                        return $user->last_login;
                    }
                    else{
                        return "Never";
                    }
                })                
                ->addColumn('action', function ($user) {
                    return "
                        <a href='". url("user/".$user->id."/edit")."' class='btn btn-sm btn-warning' rel='tooltip' title='Edit'><i class='fa fa-edit'></i> </a>
                        <button class='btn-delete btn btn-danger btn-sm' data-remote='/user/" . $user->id . "' rel='tooltip' title='Delete'><i class='fa fa-trash-o'></i> </button>
                    ";
                })
                ->addColumn('avatar', function ($user) {
                    return "
                       <img class='img-circle' src='".Baselib::get_gravatar($user->email, $s = 25)."'>
                    ";
                })                
                ->remove_column('id')
                ->make(true);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
      
        \View::share('breadcrumbs', [
            ['name' => 'Users']
        ]);          
        return view('user.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $all_agencies = array();
        if(Baselib::isAgencyUser()) {
            $logged_in_user = \Baselib::getUser(\Auth::user()->id);
            $all_agencies   = $logged_in_user->agencies->all();

            $agency_options = $logged_in_user->agencies()->orderBy('id','asc')->pluck('name', 'id')->all();
            $acc_types      = DB::table('roles')->where('id', Role::AGENCY_USER)->orderBy('id','asc')->pluck('name', 'id');

        }else{
            // all agencies
            $all_agencies = \App\Agency::all();
            $agency_options = $all_agencies->sortBy('id')->pluck('name', 'id')->all();
            $acc_types      = DB::table('roles')->orderBy('id','asc')->pluck('name', 'id');
        }

        \View::share('breadcrumbs', [
            ['url' => route('user.index'), 'name' => 'Users']
        ]);          
        return view('user.create', array('all_agencies' => $all_agencies, 'agency_options' => $agency_options, 'acc_types' => $acc_types));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
       
        if(!isset($request['blocked'])||empty($request['blocked'])){
            $request['blocked']=0;
        }
        if(!isset($request['can_viewas'])||empty($request['can_viewas'])){
            $request['can_viewas']=0;
        }
        if(!isset($request['can_manage_user'])||empty($request['can_manage_user'])){
            $request['can_manage_user']=0;
        }        
        
        $this->validate($request, [
            'name' => 'required',
            'username' => 'required|unique:users|min:6|max:32',
            'password' => 'required|min:6',
            'email' => 'required|unique:users|email',
            'blocked' => 'required|digits:1',
            'can_viewas' => 'required|digits:1',
            'can_manage_user' => 'required|digits:1',            
            'role_id' => 'required|digits:1',
            'agency_id' => 'required'
        ]);        
        
        $input = $request->all();
        
        //force username and email to lower case
        $input['username'] = strtolower($input['username']);
        $input['email'] = strtolower($input['email']);

        // hash password
        $input['password'] = Hash::make($input['password']);

        // defaults
        $input['pagination'] = 25;
        $input['num_cutoff'] = 2;
        $input['site_skin'] = 'skin-purple';
        $input['menubar_collapse'] = 0;

        $user = new User();
        $user->fill($input)->save();

        // assign agency
        $user->agencies()->attach($input['agency_id']);

        $assignedAgencies = array();
        // if user belongs to accuen then check for multiple agencies
        if($input['agency_id'] == 10){
            $assignedAgencies = $input['agencies'];

        }

        // assign multiple agencies (accuen agency users only)
        foreach ($assignedAgencies as $agencyId){
            $user->agencies()->attach($agencyId);
        }

        // if its an agency user and not belonging to accuen
        // then assign to selected clients
        $clientIds = array();
        if($input['role_id'] == Role::AGENCY_USER && $input['agency_id'] != 10){
            if(array_key_exists('clients', $input)){
                $clientIds = $input['clients'];
            }else{
                $clientIds = array();
            }

        }

        foreach ($clientIds as $clientId){
            $user->clients()->attach($clientId);
        }

        //check if record is created and get id
        $new_id = DB::table("users")->where("username", $input['username'])->value("id");

        if(!empty($new_id)){
            //Now send mail with password to the user email address
            $this->sendMail($input);
            return \Redirect::to('user')->with('success', 'User <b>'.$input['name'].'</b> has been successfully created. An email with login credentials has been sent to <b>'.$input['email'].'</b>');
        }

        return \Redirect::to('user')->with('error', 'Problem creating user, please try again!');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        \View::share('breadcrumbs', [
            ['url' => route('user.index'), 'name' => 'Users']
        ]);        
        $user = User::findOrFail($id);

        if(Baselib::isAgencyUser()) {
            $logged_in_user             = \Baselib::getUser(\Auth::user()->id);
            $logged_in_users_agency_ids = $logged_in_user->agencies()->orderBy('id')->pluck('id')->all();

            $users_agency_ids = $user->agencies()->orderBy('id')->pluck('id')->all();

            foreach ($users_agency_ids as $agency_id){
                if(in_array($agency_id, $logged_in_users_agency_ids) == false){
                    // not allowed to edit this user
                    return redirect()->route('dashboard');
                }
            }

            $all_agencies   = $logged_in_user->agencies->all();
            $agency_options = $logged_in_user->agencies()->orderBy('id','asc')->pluck('name', 'id')->all();
            $acc_types      = DB::table('roles')->where('id', Role::AGENCY_USER)->orderBy('id','asc')->pluck('name', 'id');

        }else{
            // all agencies
            $all_agencies = \App\Agency::all();
            $agency_options = $all_agencies->sortBy('id')->pluck('name', 'id')->all();
            $acc_types      = DB::table('roles')->orderBy('id','asc')->pluck('name', 'id');
        }

        return view('user.edit', array('all_agencies' => $all_agencies, 'agency_options' => $agency_options, 'user' => $user, 'acc_types' => $acc_types));
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
  
        $user = User::findOrFail($id);

        if(Baselib::isAgencyUser()) {
            $logged_in_user             = \Baselib::getUser(\Auth::user()->id);
            $logged_in_users_agency_ids = $logged_in_user->agencies()->orderBy('id')->pluck('id')->all();

            $users_agency_ids = $user->agencies()->orderBy('id')->pluck('id')->all();

            foreach ($users_agency_ids as $agency_id){
                if(in_array($agency_id, $logged_in_users_agency_ids) == false){
                    // not allowed to edit this user
                    return redirect()->route('dashboard');
                }
            }
        }

        if(!isset($request['blocked'])||empty($request['blocked'])){
            $request['blocked']=0;
        }
        if(!isset($request['can_viewas'])||empty($request['can_viewas'])){
            $request['can_viewas']=0;
        }
        if(!isset($request['can_manage_user'])||empty($request['can_manage_user'])){
            $request['can_manage_user']=0;
        }  
        $this->validate($request, [
            'name' => 'required',
            'username' => 'required|min:6|max:32|unique:users,username,'.$id,
            'newPassword' => 'required|min:6',
            'email' => 'required|email',
            'email' => 'required|unique:users,email,'.$id,
            'blocked' => 'required|digits:1',
            'can_viewas' => 'required|digits:1',
            'can_manage_user' => 'required|digits:1',            
            'role_id' => 'required|digits:1',
            'agency_id' => 'required'
        ]); 

        $input = $request->all();
        //dd($input);
        //force username and email to lower case
        $input['username'] = strtolower($input['username']);
        $input['email'] = strtolower($input['email']);         
        
        if($input['newPassword']!=="********"){
            $input['password'] = Hash::make($input['newPassword']);
        }        

        $user->fill($input)->save();

        // assign agency
        $agency_ids = array($input['agency_id']);

        // if user belongs to accuen then check for multiple agencies
        if($input['agency_id'] == 10){
            if(count($input['agencies']) > 0){
                foreach ($input['agencies'] as $agency_id){
                    $agency_ids[] = $agency_id;
                }
            }
        }

        // save agency/agencies
        $user->agencies()->sync($agency_ids);

        // if its an agency user and not belonging to accuen
        // then assign to selected clients
        $client_ids = array();
        if($input['role_id'] == Role::AGENCY_USER && $input['agency_id'] != 10){
            if(array_key_exists('clients', $input)){
                if(count($input['clients']) > 0) {
                    foreach ($input['clients'] as $client_id) {
                        $client_ids[] = $client_id;
                    }
                }else{
                    $client_ids = array();
                }
            }else{
                $client_ids = array();
            }

        }

        // save clients to user
        $user->clients()->sync($client_ids);

        return \Redirect::to('user')->with('success', 'User <b>'.$input['name'].'</b> has been successfully updated!');       
    }
    
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function updatePref(Request $request)
    {
        $user = User::findOrFail(\Auth::user()->id);

        if(!isset($request['menubar_collapse'])||empty($request['menubar_collapse'])){
            $request['menubar_collapse']=0;
        }
        
        $input = $request->all();
        $user->fill($input)->save();
        return \Redirect::back()->with('success', 'Display setting was successfully updated!');       
    }    

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
     
        $user = User::findOrFail($id);

        if(Baselib::isAgencyUser()) {
            $logged_in_user             = \Baselib::getUser(\Auth::user()->id);
            $logged_in_users_agency_ids = $logged_in_user->agencies()->orderBy('id')->pluck('id')->all();

            $users_agency_ids = $user->agencies()->orderBy('id')->pluck('id')->all();

            foreach ($users_agency_ids as $agency_id){
                if(in_array($agency_id, $logged_in_users_agency_ids) == false){
                    // not allowed to edit this user
                    return redirect()->route('dashboard');
                }
            }
        }

        $user->delete();
        return \Redirect::to('user')->with('success', 'User successfully deleted!');
    }
    
    public function delete($id)
    {
       
        $user = User::findOrFail($id);

        if(Baselib::isAgencyUser()) {
            $logged_in_user             = \Baselib::getUser(\Auth::user()->id);
            $logged_in_users_agency_ids = $logged_in_user->agencies()->orderBy('id')->pluck('id')->all();

            $users_agency_ids = $user->agencies()->orderBy('id')->pluck('id')->all();

            foreach ($users_agency_ids as $agency_id){
                if(in_array($agency_id, $logged_in_users_agency_ids) == false){
                    // not allowed to edit this user
                    return redirect()->route('dashboard');
                }
            }
        }

        $user->delete();
        return \Redirect::to('user')->with('success', 'User successfully deleted!');
    }
    
    public function autocomplete(){
        $term = \Input::get('term');
        $results = array();
        $queries = DB::table('users')
                ->where('name', 'ILIKE', '%'.$term.'%')
                ->orderBy('name', 'asc')
                ->get();

        foreach ($queries as $query)
        {
            $results[] = [ 'id' => $query->id, 'value' => $query->name ];
        }
        
        return \Response::json($results);
    }    

    private function sendMail($user){
            // Prepare the e-mail to be sent and send it
            Mail::send('emails.newuser', $user, function($message) use ($user)
            {
                $message->to($user['email']);
                $message->subject('Your Minerva credentials');
            });        
    }
}

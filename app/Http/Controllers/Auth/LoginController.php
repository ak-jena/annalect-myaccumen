<?php

namespace App\Http\Controllers\Auth;

use Adldap\Adldap;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use DB;
use Session;
use Auth;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest', ['except' => 'logout']);
    }
    
    //Over-ride to log user in by username, not email address
    public function username()
    {
        return 'username';
    }    
    
    /**
     * Handle a login request to the application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function login(Request $request)
    {
        //check if user account is blocked
        $is_blocked=DB::table('users')->where('username', $request['username'])->value('blocked');
        if($is_blocked){
            return \Redirect::back()->withErrors(array('login_failed' => 'This account is currently blocked!'));
        }        
        $this->validateLogin($request);

        $credentials = $this->credentials($request);
        
        if(\Auth::attempt($credentials, false)){
//        var_dump($request->only(['username', 'password']));die;
//        if(Adldap::auth()->attempt($request['username'], $request['password'])){
//        if (Auth::attempt($credentials)) {
            // Authentication passed, update last_login
            $now = date("Y-m-d H:i:s");
            DB::table('users')->where('username',$request['username'])->update(array('last_login'=>$now));
            //Get user role and store it in Session for easy access
//            $role=DB::table('roles')->where('id', Auth::user()->role_id)->value('name');
            $role=DB::table('roles')->where('id', Auth::user()->role_id)->first();
            Session::put('user_role_name', $role->name);
            Session::put('user_role_id', $role->id);


            //set notifications & announcements
            $this->setNotifications();
            $this->setAnnouncements();
            //redirect to dashboard
            return redirect()->intended('/');
        } else{
            return \Redirect::back()->withErrors(array('login_failed' => 'Incorrect username or password!'));
        }

    }

    /**
     * We do this here one time only for performance reason
     */
    protected function setNotifications()
    {
//        $notifications=1;
//        $notification_msg=array();
//        $notification_msg[$notifications]['text']="A sample notification";
//        $notification_msg[$notifications]['url']="users";
//        $notification_msg[$notifications]['icon']="<i class='fa fa-users text-yellow'></i>";
//
//        Session::put('notifications', $notifications);
//        Session::put('notification_msg', $notification_msg);
    }
    /**
     * We do this here one time only for performance reason
     */
    protected function setAnnouncements()
    {
        $announcements=0;
        $announcement_msg=array();

        $sql = "SELECT * FROM announcements
                WHERE (user_group=0 OR user_group=".Auth::user()->role_id.") AND start_date<=UNIX_TIMESTAMP() AND end_date>=CURDATE() AND is_active=1 ORDER BY start_date DESC LIMIT 5";

        $results = DB::select($sql);

        foreach($results as $result){
            $announcement_msg[$announcements]['text']=$result->message;
            $announcement_msg[$announcements]['url']=$result->url;
            $announcement_msg[$announcements]['icon']=$result->icon;
            $announcements++;
        }
        
        
        Session::put('announcements', $announcements);
        Session::put('announcement_msg', $announcement_msg);
    }    
    
}

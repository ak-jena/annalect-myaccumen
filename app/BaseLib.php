<?php

namespace App;

use Session;
use DB;
use Auth;

class BaseLib {
    
    const TODAY = 'Today';
    const THIS_WEEK = 'This week';
    const THIS_MONTH = 'This month';    
    const TOMORROW = 'Tomorrow';
    const NEXT_WEEK = 'Next week';
    const NEXT_MONTH = 'Next month';    
    
    /* BELOW FUNCTIONS ARE FOR AUTHORISATION PURPOSES */
    
    //Get current user ID: return View-As first. If not set, then return real user ID.
    public static function getUserID(){
        if(Session::has('viewas_id')){
            return Session::get('viewas_id');
        }
        return Auth::user()->id;
    }

    //Get real user ID, not View-As
    public static function getRealUserID(){
         return Auth::user()->id;
    }     

    //Get real user, not View-As
    public static function getRealUserFirstName(){
         return strtok(Auth::user()->name,' ');
    }    
    
    //Retain real user role before using View-As
    public static function retainRealRole(){
        
        if(Session::has('user_role_id')){
            Session::set('real_user_role_id', Session::get('user_role_id'));
            Session::set('real_user_role_name', Session::get('user_role_name'));

            return TRUE;
        }
        return FALSE;
    }
    
    //Retain real user role after using View-As
    public static function revertRealRole(){
        
        if(Session::has('real_user_role_id')){
            Session::set('user_role_name', Session::get('real_user_role_name'));
            Session::set('user_role_id', Session::get('real_user_role_id'));

            Session::forget('real_user_role_name');
            Session::forget('real_user_role_id');
            return TRUE;
        }
        return FALSE;
    }    
    
    public static function isDeveloperUser(){
        
        if(Session::get('user_role_name')==='Developer' || Session::get('user_role_name')==='Analyst'){
            return true;
        }
        return false;
    }

    public static function isManagerUser(){
        
        if(Session::get('user_role_name')==='Manager'){
            return true;
        }
        return false;
    }      
    
    public static function isInventoryUser(){
        
        if(Session::get('user_role_name')==='Inventory'){
            return true;
        }
        return false;
    }
    
    public static function isTraderUser(){
        if(Session::get('user_role_name')==='Trader'){
            return true;
        }
        return false;
    }

    public static function isAgencyUser(){
        if(Session::get('user_role_name')==='Agency User'){
            return true;
        }
        return false;
    }

    public static function isActivationUser(){
        if(Session::get('user_role_name')==='Activation User'){
            return true;
        }
        return false;
    }

    public static function isActivationLineManager(){
        if(Session::get('user_role_name')==='Activation Line Manager'){
            return true;
        }
        return false;
    }

    public static function isHeadOfActivation(){
        if(Session::get('user_role_name')==='Head of Activation'){
            return true;
        }
        return false;
    }

    public static function isVodUser(){
        if(Session::get('user_role_name')==='VOD User'){
            return true;
        }
        return false;
    }

    public static function hasUserMgmt(){
        return \Auth::user()->can_manage_user;
    }

    public static function hasViewAs(){
        return \Auth::user()->can_viewas;
    }

    /**
     * Indicates if a user is allowed to create a brief (based on their role)
     *
     * @return boolean
     *
     * @author Saeed Bhuta
     * @version 20170530
     */
    public static function canCreateBrief(){
        $role = Session::get('user_role_name');
        if(in_array($role,array('Developer', 'Agency User', 'VOD User'))){
            return true;
        }
        return false;
    }

    /**
     * Indicates if a user is allowed to upload targeting grid(s) (based on their role)
     *
     * @return boolean
     *
     * @author Saeed Bhuta
     * @version 20170530
     */
    public static function canUploadGrid(){
        $role = Session::get('user_role_name');
        if(in_array($role,array('Developer', 'Activation User', 'Activation Line Manager', 'Head of Activation', 'VOD User'))){
            return true;
        }
        return false;
    }

    /**
     * Indicates if a user is allowed to approve booking (based on their role)
     *
     * @return boolean
     *
     * @author Saeed Bhuta
     * @version 20170530
     */
    public static function canApproveBooking(){
        $role = Session::get('user_role_name');
        if(in_array($role,array('Developer', 'Activation User', 'Activation Line Manager', 'VOD User'))){
            return true;
        }
        return false;
    }

    /**
     * Indicates if a user is allowed to approve or reject targeting grid (based on their role)
     *
     * @return boolean
     *
     * @author Saeed Bhuta
     * @version 20170706
     */
    public static function canApproveTargetingGrid(){
        $role = Session::get('user_role_name');
        if(in_array($role,array('Developer', 'Activation Line Manager', 'Agency User', 'VOD User', 'Head of Activation'))){
            return true;
        }
        return false;
    }

    /**
     * Indicates if a user is allowed to save booking data (based on their role)
     *
     * @return boolean
     *
     * @author Saeed Bhuta
     * @version 20170530
     */
    public static function canCreateBooking(){
        $role = Session::get('user_role_name');
        if(in_array($role,array('Developer', 'Agency User'))){
            return true;
        }
        return false;
    }

    /**
     * Indicates if a user is allowed to save IO data (based on their role)
     *
     * @return boolean
     *
     * @author Saeed Bhuta
     * @version 20170530
     */
    public static function canCreateIo(){
        $role = Session::get('user_role_name');
        if(in_array($role,array('Developer', 'Activation User', 'Activation Line Manager', 'Agency User', 'VOD User'))){
            return true;
        }
        return false;
    }

    /**
     * Indicates if a user is allowed to save Creative tags data (based on their role)
     *
     * @return boolean
     *
     * @author Saeed Bhuta
     * @version 20170530
     */
    public static function canCreateTags(){
        $role = Session::get('user_role_name');
        if(in_array($role,array('Developer', 'Agency User', 'VOD User', 'Activation Line Manager'))){
            return true;
        }
        return false;
    }

    /* END OF AUTHORISATION PURPOSES */
    
    
    public static function getUserOld($id){
        $query = "SELECT name, username, email, last_login, blocked, can_viewas, can_manage_user, role_id FROM users WHERE id=".$id. " LIMIT 1";
        $result = DB::select($query);
        return $result[0];
    }

    public static function getUser($id){
//        $user = DB::table('users')->where('id', $id)->first();
        $user = \App\User::find($id);
        return $user;
    }

    public static function getDSP($id){
        $query = "SELECT name, logo_file, db_prefix FROM minerva_all.dsp WHERE id=".$id. " LIMIT 1";
        $result = DB::select($query);
        return $result[0];
    }     
    
    
    function normalize_seconds($sec) {
        $ss = $sec % 60;
        $mm = ($sec - $ss) / 60;
        return sprintf("%02d:%02d", $mm, $ss);
    }      
    
    public static function get_gravatar( $email, $s = 215, $d = 'mm', $r = 'x', $img = false, $atts = array() ) {
    /**
     * Get either a Gravatar URL or complete image tag for a specified email address.
     *
     * @param string $email The email address
     * @param string $s Size in pixels, defaults to 80px [ 1 - 2048 ]
     * @param string $d Default imageset to use [ 404 | mm | identicon | monsterid | wavatar ]
     * @param string $r Maximum rating (inclusive) [ g | pg | r | x ]
     * @param boole $img True to return a complete IMG tag False for just the URL
     * @param array $atts Optional, additional key/value attributes to include in the IMG tag
     * @return String containing either just a URL or a complete image tag
     * @source https://gravatar.com/site/implement/images/php/
     */        
        $url = 'https://www.gravatar.com/avatar/';
        $url .= md5( strtolower( trim( $email ) ) );
        $url .= "?s=$s&d=$d&r=$r";
        if ( $img ) {
            $url = '<img src="' . $url . '"';
            foreach ( $atts as $key => $val )
                $url .= ' ' . $key . '="' . $val . '"';
            $url .= ' />';
        }
        return $url;
    }

    public static function removeElementWithValue($array, $key, $value){
        foreach($array as $subKey => $subArray){
            if($subArray[$key] == $value){
                unset($array[$subKey]);
            }
        }
        return $array;
    }

    public static function replace_str_in_file($file, $t1, $t2){
    /**
     * Replaces a string in a file
     * @param string $file
     * @param string $t1 text to be replaced
     * @param string $t2 text to replace
     * @return array $result status (success | error) & message (file exist, file permissions)
     */        
        $result = array('status' => 'error', 'message' => '');
        if(file_exists($file)===TRUE){
            if(is_writeable($file)){
                try{
                    $content = file_get_contents($file);
                    $content = str_replace($t1, $t2, $content);
                    if(file_put_contents($file, $content) > 0){
                        $result["status"] = 'success';    
                    }
                    else{
                       $result["message"] = 'Error while writing file'; 
                    }
                }
                catch(Exception $e){
                    $result["message"] = 'Error : '.$e; 
                }
            }
            else{
                $result["message"] = 'File '.$file.' is not writable !';       
            }
        }
        else{
            $result["message"] = 'File '.$file.' does not exist !';
        }
        return $result;
    }

    public static function getMonthsFromQuarter($quarter){
        switch($quarter) {
            case 1: return array('1', '2', '3');
            case 2: return array('4', '5', '6');
            case 3: return array('7', '8', '9');
            case 4: return array('10', '11', '12');
        }
    }
    
    //convert a numeric value from 0-100 to visual success/warning/alert colors. Note: low value is good.
    public static function number2Color($num){
        if($num<10){
            return "#00a65a"; //green
        }
        else if($num<30){
            return "#00c0ef"; //aqua
        }        
        else if($num<50){
            return "#3c8dbc"; //blue
        }
        else if($num<70){
            return "#f39c12"; //yellow
        }        
        else{
            return "#f56954"; //red
        }
    }


}
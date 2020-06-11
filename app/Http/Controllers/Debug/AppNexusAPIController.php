<?php

/* 
 * @author Tuan Nguyen <tuan.nguyen@accuenmedia.com>
 * @copyright 2016 AccuenUK
 */

namespace App\Http\Controllers\Debug;

use App\Http\Controllers\Controller;
use GuzzleHttp\Client;
use Cache;
use Log;

class AppNexusAPIController extends Controller {
    
    private $baseurl;
    private $authurl;
    private $dsp;
    private $api_username;
    private $api_password;
    

    function __construct() {
            $this->init();
    }

    private function init() {
        
        $this->dsp = 1;
        $this->baseurl = 'https://api.appnexus.com/';
        $this->authurl = 'http://api.appnexus.com/auth';
        $this->api_username = 'Username';
        $this->api_password = 'Password';
        
    }    
    
    public function getAPNToken(){

        $token = Cache::get('appnexus_auth_token', function(){
            
            $auth_json = json_encode(
                array(
                    'auth' =>[
                        'username'=> $this->api_username,
                        'password'=> $this->api_password
                    ]    
                )
            );        

            $client = new Client();        

            $response = $client->request('POST', $this->authurl, 
                [
                    'headers'  => ['Content-Type' => 'application/json'],
                    'body' => $auth_json
                ]
            );

            if($response->getStatusCode()==200){
                $body = json_decode($response->getBody());

                Log::debug('Getting new auth token for Appnexus: '.$body->response->token);
            }
            else{
                abort(599, 'AppNexus API authentication failed with code: '.$response->getStatusCode());
            }                    

            Cache::put('appnexus_auth_token', $body->response->token, 115); //remember it for 115 minutes as AppNexus token is good for 120 minutes
            return $body->response->token;
        });

        return $token;
    }    
    
    public function index(){
        $result = $this->invokeAPICall('GET', 'deal-buyer-access');
        dd($result);
    }
    
    public function invokeAPICall($method, $link, $params=NULL){

        if($method==='POST'||$method==='PUT'){
            $url = $this->baseurl.$link;
            $body_json = json_encode($params);        

            $client = new Client();
            
            try {
                $response = $client->request($method, $url, 
                    [
                        'headers' => ['Authorization' => $this->getAPNToken(), 'Content-Type' => 'application/json'],
                        'body' => $body_json
                    ]
                );        
                if($response->getStatusCode()==200){
                    $body = json_decode($response->getBody());
                    return $body;
                }
                else{
                    return NULL;
                }     
            } catch (\GuzzleHttp\Exception\BadResponseException $e) {
                abort(599, $e->getMessage());
            }            
        }
        else if($method==='GET'){
            $url = $this->baseurl.$link;
            
            $client = new Client(['headers' => ['Authorization' => $this->getAPNToken()]]);
            try {            
                $response = $client->get($url);

                if($response->getStatusCode()==200){
                    $body = json_decode($response->getBody());
                    return $body;
                }
                else{
                    return NULL;
                } 
            } catch (\GuzzleHttp\Exception\BadResponseException $e) {
                abort(599, $e->getMessage());
            }                 
        }
    }    
}

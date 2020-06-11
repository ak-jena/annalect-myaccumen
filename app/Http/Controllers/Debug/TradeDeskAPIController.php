<?php

namespace App\Http\Controllers\Debug;

use App\Http\Controllers\Controller;
use GuzzleHttp\Client;
use Cache;
use Log;

class TradeDeskAPIController extends Controller {
    
    private $baseurl;
    private $authurl;
    private $partnerID;
    private $dsp;
    private $api_username;
    private $api_password;    
    

    function __construct() {
            $this->init();
    }

    private function init() {
        
        $this->dsp = 5;
        $this->partnerID = 'm0zwsnz';
        $this->baseurl = 'https://api.thetradedesk.com/v3/';
        $this->authurl = 'https://api.thetradedesk.com/v3/authentication/';
        $this->api_username = 'Username';
        $this->api_password = 'Password';        
        
    }
    
    public function getTTDToken(){

        $token = Cache::get('tradedesk_auth_token', function(){
            
            $auth_json = json_encode(
                array(
                    'Login'=> $this->api_username,
                    'Password'=> $this->api_password,
                    'TokenExpirationInMinutes'=>120
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

                Log::debug('Getting new auth token for TheTradeDesk: '.$body->Token);
            }
            else{
                abort(599, 'TheTradeDesk API authentication failed with code: '.$response->getStatusCode());
            }                    

            Cache::put('tradedesk_auth_token', $body->Token, 115); //remember it for 115 minutes as token is good for 120 minutes, see above.
            return $body->Token;
        });

        return $token;
    }        
    
    public function index(){
        
        /*
        $result = $this->invokeAPICall('GET', 'category/industrycategories');        
        dd($result);
         * 
         */
        
        $params = array(
                    'PartnerId'=>$this->partnerID,
                    'PageStartIndex'=>1,
                    'PageSize'=>10
                  );
        $result = $this->invokeAPICall('POST', 'contract/query/partner', $params);
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
                        'headers' => ['TTD-Auth' => $this->getTTDToken(), 'Content-Type' => 'application/json'],
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
            
            $client = new Client(['headers' => ['TTD-Auth' => $this->getTTDToken()]]);
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

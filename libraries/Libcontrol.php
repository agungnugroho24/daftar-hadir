<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
 
    class Libcontrol 
    {
        var $ci;
        var $app        = 'daftarhadir';
        var $apikey     = 'F8iPcsqVlqPlOdTjIH4xGhWUNVwK2c5CKXht5EuWezJbyCZNEpHHI3ChSenNxhgPaitS4YXwlTtTRnxtHi%21Nyg==';
        var $sess_id    = '';
         
        function __construct() 
        {
            $this->ci =& get_instance();
            $this->setCookies();
        }
        
        private function setCookies(){
            $this->ci->load->helper('cookie');
            $cookies = get_cookie('um-bp');
            $this->sess_id = substr($cookies, strpos($cookies, "32:") + 4, 32);
        }
        function getData(){
            $isian = array( 'session' => $this->sess_id,
                            'app'      => $this->app,
                            'apikey'   => $this->apikey);
            $url = "https://akun.bappenas.go.id/bp-um/service/checkSession";
            return $this->postData($isian, $url);
        }
        function deleteSession(){
            $isian = array( 'session' => $this->sess_id,
                            'app'      => $this->app,
                            'apikey'   => $this->apikey);
            $url = "https://akun.bappenas.go.id/bp-um/service/deleteSession";
            unset($_COOKIE["um-bp"]);
            return $this->postData($isian, $url);
            
        }
        function getUserapp(){
            $isian = array( 'username' => $this->getData()->data[0]->userid,
                            'app'      => $this->app,
                            'apikey'   => $this->apikey);
            $url = "https://akun.bappenas.go.id/bp-um/service/getUserApp";
            return $this->postData($isian, $url);
        }

        function getuserdata($uid){
            $isian = array( 'username' => $uid,
                            'app'      => $this->app,
                            'apikey'   => $this->apikey);
            $url = "https://akun.bappenas.go.id/bp-um/service/checkUser";
            return $this->postData($isian, $url);
        }
        
        private function postData($data, $url){
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS,$data);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            $output = curl_exec ($ch);
            curl_close ($ch);
            $hasil = json_decode($output);
            return $hasil;
        }
    }
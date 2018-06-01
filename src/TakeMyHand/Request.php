<?php
/**
 *
 *  _____     _       _____     _____           _
 * |_   _|___| |_ ___|     |_ _|  |  |___ ___ _| |
 *  | | | .'| '_| -_| | | | | |     | .'|   | . |
 *  |_| |__,|_,_|___|_|_|_|_  |__|__|__,|_|_|___|
 *                        |___|
 *
 * TakeMyHand  Boilerplate-Nano-Framework v0.0.3
 * @category   FrameworkBasics
 * @package    TakeMyHand
 * @author     Jan Caspar, <jan@subkonstrukt.at>
 * @copyright  2018 Jan Caspar
 * @license    https://opensource.org/licenses/MIT MIT
 * @version    v0.0.3
 * @since      v0.0.1
 *
 * Basic Request, creates a 'abstract' request,
 * this is still missing several implementation to be a full http request
 * representation
 *
 */

namespace TakeMyHand;

use ReflectionClass;
use ReflectionFunction;
use TakeMyHand\Binding\IBinder;

class Request{
    private $cookies = array();
    private $headers = array();
    private $user_host;
    private $method;
    private $uri;
    private $body;
    private $query_string;
    private $protocol;

    public function __construct(){
        $this->parse_request();
        $this->parse_headers();
    }

    private function parse_headers() 
    { 
        $this->headers = Polyfill\ApachePolyfillUtility::polyfill_headers();
    } 

    private function parse_request(){
        $this->method = $_SERVER['REQUEST_METHOD'];
        $this->query_string = $_SERVER['QUERY_STRING'];
        $this->uri =  $_SERVER['REQUEST_URI'];
        $this->protocol =  (!empty( $s['HTTPS'] ) && $s['HTTPS'] == 'on') ? 'https' : 'http';
        $this->user_host = $_SERVER['REMOTE_ADDR'];
        //accessing the request body is kind of cumbersome
        //taken from http://php.net/manual/en/wrappers.php.php
        if($this->method == 'POST' || $this->method == 'PUT'){
            $this->body = file_get_contents('php://input');
        }
    }

    public function get_query_string() : string {
        return $this->query_string;
    }

    public function get_method() : string {
        return $this->method;
    }

    public function get_uri(){
        return $this->uri;
    }

    public function get_body(){
        return $this->body;
    }

    public function cookie_exists($key) : bool{
        return isset($_COOKIE['key']);
    }

    public function get_cookie($key){
        return $_COOKIE['key'];
    }

    private function bind_body_json($type){
        if($type === 'assoc'){
            return json_decode($this->body,true);
        }else{
            $doc = new $type();
            $obj = json_decode($this->body,true);
            foreach($obj as $key => $value){
                if(method_exists ($doc,'set_'.$key)){
                    $doc->{'set_'.$key}($value);
                }
            }
            return $doc;
        }
    }

    private function bind_body_form($type){
        if($type === 'assoc'){
            return $this->privatize_super_global();
        }else{
            $doc = new $type();
            $obj = $this->privatize_super_global();
            foreach($obj as $key => $value){
                if(method_exists ($doc,'set_'.$key)){
                    $doc->{'set_'.$key}($value);
                }
            }
            return $doc;
        }
    }

    private function privatize_super_global() : array{
        $assoc = array();
        foreach($_REQUEST as $k => $v){
            $assoc[$k] = $v;
        }
        return $assoc;
    }

    public function bind_body($type){
        if(preg_match('/\/json/',$this->headers['Content-Type'])){
            return $this->bind_body_json($type);
        }
        if(preg_match('/\/x-www-form-urlencoded/',$this->headers['Content-Type'])
            || preg_match('/\/form-data/',$this->headers['Content-Type'])){
            return $this->bind_body_form($this->privatize_super_global());
        }
        return null;
    }

    public function bind_body_with(IBinder $binder){
        if(preg_match('/\/json/',$this->headers['Content-Type'])){
            return $binder->bind(json_decode($this->body,true));
        }
        if(preg_match('/\/x-www-form-urlencoded/',$this->headers['Content-Type'])
            || preg_match('/\/form-data/',$this->headers['Content-Type'])){
            return $binder->bind($this->privatize_super_global());
        }
        return null;

    }

}
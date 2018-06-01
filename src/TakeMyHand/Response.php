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
 * This is the base for responses
 *
 */


namespace TakeMyHand;

use DateTimeImmutable;
use TakeMyHand\Http\IHeaders;

abstract class Response implements IHeaders {
    protected $status_code = 200;
    protected $body;
    protected $needs_view_engine = false;

    private $headers = array();

    public function requires_rendering() : bool {
        return $this->needs_view_engine;
    }

    public function set_status_code(int $status_code){
        $this->status_code = $status_code;
        http_response_code($this->status_code);
    }

    private function parse_existing_headers()
    {
        $header = array();
        foreach($http_response_header as $value )
        {
            $explode = explode(':', $value,2 );
            if(isset($explode[1])){
                $header[trim($explode[0])] = trim($explode[1]);
            }

        }
        return $header;
    }

    public function get_header($key)
    {
        if(array_key_exists($key,$this->headers)){
            return $this->headers[$key];
        }
        $existing_headers = $this->parse_existing_headers();
        if(array_key_exists($key,$existing_headers)){
            return $existing_headers[$key];
        }
        return null;
    }

    public function set_header($key, $value){
        $this->headers[$key] = $value;
    }

    public function get_status_code() : int{
        return $this->status_code;
    }

    public function set_cookie($key, $value, \DateInterval $ttl){
        $reference = new \DateTime('now');
        setcookie($key,$value, $reference->add($ttl)->getTimestamp() - $reference->getTimestamp());
    }

    public function write()
    {
        foreach($this->headers as $key => $value){
            header($key.':'.$value,true);
        }
        ob_start();
        echo $this->body;
        ob_end_flush();
    }
}
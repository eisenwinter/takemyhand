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
 * @since      v0.0.3
 *
 * Static utility to unify the headers
 *
 */


namespace TakeMyHand\Polyfill;


class ApachePolyfillUtility
{
    public static function polyfill_headers() : array{
        $headers = array();
        foreach($_SERVER as $key=>$value) {
            if (substr($key,0,5)=="HTTP_") {
                $key=str_replace(" ","-",ucwords(strtolower(str_replace("_"," ",substr($key,5)))));
                $headers[$key]=$value;
            }
            elseif(substr($key,0,8)=="CONTENT_") {
                $key=str_replace(" ","-",ucwords(strtolower(str_replace("_"," ",$key))));
                $headers[$key]=$value;
            }
            else{
                $headers[$key]=$value;
            }
        }
        return $headers;
    }
}
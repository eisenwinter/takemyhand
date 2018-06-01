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
 * uses content negotiation to render the request,
 * this needs to be fixed up because the content type matching is
 * simply awfull and not working on all browsers
 *
 */


namespace TakeMyHand\Responses;


use TakeMyHand\Polyfill\ApachePolyfillUtility;
use TakeMyHand\Response;
use TakeMyHand\ViewEngine\IViewEngine;

class NegotiatedResponse extends Response
{
    private $inner_response;


    public function __construct($data,string $view)
    {
        $headers = ApachePolyfillUtility::polyfill_headers();
        if( array_key_exists('Content-Type', $headers) && !empty($headers['Content-Type'])){
            switch(strtolower($headers['Content-Type'])){
                case 'application/json':
                case 'application/javascript':
                case 'text/json':
                case 'text/javascript':
                case 'application/json; charset=utf-8':
                case 'application/javascript; charset=utf-8':
                case 'application/json; charset=utf-8;':
                case 'application/javascript; charset=utf-8;':
                    $this->inner_response = new JsonResponse($data);
                    break;
                case 'text/html':
                case 'text/html; charset=utf-8':
                    $this->inner_response = new ViewResponse($view, $data);
                    $this->needs_view_engine = true;
                    break;
                default:
                    $this->inner_response = new StatusCodeResponse($this->get_status_code());
                    break;
            }

        }else{
            $this->inner_response = new ViewResponse($view,$data);
            $this->needs_view_engine = true;
        }
    }

    public function write(){
        $this->inner_response->write();
    }


    public function render(IViewEngine $engine){
        $this->inner_response->render($engine);
    }
}
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
 * @category   Subscribers
 * @package    TakeMyHand.InternalSubscribers
 * @author     Jan Caspar, <jan@subkonstrukt.at>
 * @copyright  2018 Jan Caspar
 * @license    https://opensource.org/licenses/MIT MIT
 * @version    v0.0.3
 * @since      v0.0.1
 *
 * handles the route not found messages,
 * still uses the kind-of faulty negotiated response,
 * which needs patching for better content-type matching
 *
 */


namespace TakeMyHand\InternalSubscribers;


use TakeMyHand\Dispatcher\IMessage;
use TakeMyHand\Dispatcher\ISubscribe;
use TakeMyHand\Events\ResponseReadyMessage;
use TakeMyHand\Events\RouteNotFoundMessage;
use TakeMyHand\Http\FullFailureHelper;
use TakeMyHand\Responses\NegotiatedResponse;
use TakeMyHand\Responses\PlainHtmlResponse;
use TakeMyHand\Responses\ViewResponse;

class FourOFourSubscriber implements ISubscribe
{

    function get_message_type()
    {
        return "TakeMyHand\Events\RouteNotFoundMessage";
    }

    function handle(IMessage $msg) : ?IMessage
    {
        $data = array();
        if($msg instanceof RouteNotFoundMessage){
            $data['message'] = $msg->get_route_name() . ' not found.';
        }
        if(strtolower($_ENV['TMH_CUSTOM_ERROR_PAGES']) == 'true'){
            $resp = new NegotiatedResponse($data, 'Errors/404');
            $resp->set_status_code(404);
            return new ResponseReadyMessage('FourOFourSubscriber', $resp);
        }else{
            $resp = new PlainHtmlResponse(FullFailureHelper::get_error_template('404','Page not found','The requested page does not exist',null));
            $resp->set_status_code(404);
            return new ResponseReadyMessage('FourOFourSubscriber', $resp);
        }

    }
}
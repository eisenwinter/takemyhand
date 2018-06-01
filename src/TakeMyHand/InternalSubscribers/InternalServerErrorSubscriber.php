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
 * handles the 500 responses,
 * this is the 'last' frontier of hard failing
 * probably a component failed and doesnt know how to proceed further
 *
 */


namespace TakeMyHand\InternalSubscribers;


use TakeMyHand\Dispatcher\IMessage;
use TakeMyHand\Dispatcher\ISubscribe;
use TakeMyHand\Events\InternalServerErrorMessage;
use TakeMyHand\Events\ResponseReadyMessage;
use TakeMyHand\Responses\NegotiatedResponse;
use TakeMyHand\Responses\ViewResponse;

class InternalServerErrorSubscriber  implements ISubscribe
{

    function get_message_type()
    {
        return "TakeMyHand\Events\InternalServerErrorMessage";
    }

    function handle(IMessage $msg): ?IMessage
    {
        $data = array();
        if($msg instanceof InternalServerErrorMessage){
            $data['message'] = $msg->get_details();
        }

        if(strtolower($_ENV['TMH_CUSTOM_ERROR_PAGES']) == 'true'){
            $resp = new NegotiatedResponse($data,'Errors/500');
            $resp->set_status_code(500);
            return new ResponseReadyMessage('InternalServerErrorSubscriber', $resp);
        }else{
            $resp = new PlainHtmlResponse(FullFailureHelper::get_error_template('500','Internal Server Error','Something went terrible wrong',null));
            $resp->set_status_code(500);
            return new ResponseReadyMessage('InternalServerErrorSubscriber', $resp);
        }
    }
}
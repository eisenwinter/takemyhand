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
 * catches 401 of auth
 *
 */



namespace TakeMyHand\InternalSubscribers;


use TakeMyHand\Dispatcher\IMessage;
use TakeMyHand\Dispatcher\ISubscribe;
use TakeMyHand\Events\ResponseReadyMessage;
use TakeMyHand\Http\HttpStatusCode;
use TakeMyHand\Responses\StatusCodeResponse;

class UserNotAuthorizedSubscriber implements ISubscribe
{
    public function get_message_type()
    {
        return "TakeMyHand\Events\UserNotAuthorizedMessage";
    }

    function handle(IMessage $msg): ?IMessage
    {
        $resp = new StatusCodeResponse(HttpStatusCode::Unauthorized);
        return new ResponseReadyMessage('UserNotAuthorizedSubscriber', $resp);
    }
}
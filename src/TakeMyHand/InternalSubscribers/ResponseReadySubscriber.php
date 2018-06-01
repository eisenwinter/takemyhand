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
 * this is always the last step of the request lifecycle,
 * script execution ends with this subscriber
 *
 */



namespace TakeMyHand\InternalSubscribers;


use TakeMyHand\Dispatcher\IMessage;
use TakeMyHand\Dispatcher\ISubscribe;
use TakeMyHand\Events\ResponseReadyMessage;
use TakeMyHand\ViewEngine\IViewEngine;

class ResponseReadySubscriber implements ISubscribe
{
    private $view_engine;
    public function __construct(IViewEngine $engine)
    {
        $this->view_engine = $engine;
    }

    public function get_message_type()
    {
        return "TakeMyHand\Events\ResponseReadyMessage";
    }

    public function handle(IMessage $msg) : ?IMessage
    {
        $rr = (object)$msg->get_response();
        if ($rr->requires_rendering()) {
			try{
				$rr->render($this->view_engine);
			}catch(\Exception $e){
				\TakeMyHand\Http\FullFailureHelper::fail("View Engine Error",$e->getMessage());
			}  
        }
        $rr->write();
        exit(0);
    }
}
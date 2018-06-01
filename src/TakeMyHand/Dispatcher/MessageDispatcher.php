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
 * @category   Messaging
 * @package    TakeMyHand.MessageDispatcher
 * @author     Jan Caspar, <jan@subkonstrukt.at>
 * @copyright  2018 Jan Caspar
 * @license    https://opensource.org/licenses/MIT MIT
 * @version    v0.0.3
 * @since      v0.0.1
 *
 * Concrete implementation
 *
 */
namespace  TakeMyHand\Dispatcher;

class MessageDispatcher implements IMessageDispatcher{

    private $registered_listeners = array();

    public function __construct()
    {

    }

    private function distribute(IMessage $msg){
        $events = array();
        foreach($this->resolve(get_class($msg)) as $listener){
            $event = $listener->handle($msg);
            if($event != null){
                array_push($events, $event);
            }
        }
        foreach($events as $e){
            $this->distribute($e);
        }
    }

    public function send(IMessage $msg){
        $this->distribute($msg);
    }


    private function resolve($message_type){
        if(count($this->registered_listeners[$message_type]) == 0){
            throw  new \Exception('Unknown message type: ' . $message_type);
        }
        return $this->registered_listeners[$message_type];
    }

    public function register(ISubscribe $listener){
        $key = get_class($listener);
        $listen_for = $listener->get_message_type();
        if(isset( $this->registered_listeners[$listen_for][$key] )){
            throw  new \Exception('Double register of ' . $key);
        }
        $this->registered_listeners[$listen_for][$key] = $listener;
    }
}
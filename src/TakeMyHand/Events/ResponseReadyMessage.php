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
 * @category   Messages
 * @package    TakeMyHand.Events
 * @author     Jan Caspar, <jan@subkonstrukt.at>
 * @copyright  2018 Jan Caspar
 * @license    https://opensource.org/licenses/MIT MIT
 * @version    v0.0.3
 * @since      v0.0.1
 *
 * Final message of the event chain
 *
 */


namespace TakeMyHand\Events;


use TakeMyHand\Dispatcher\IMessage;
use TakeMyHand\Response;

class ResponseReadyMessage implements IMessage
{
    private $sender_name;
    private $response;

    public  function __construct(string $sender, Response $response)
    {
        $this->sender_name = $sender;
        $this->response = $response;
    }

    function get_sender_name(): string
    {
        return $this->sender_name;
    }

    public function get_response() : Response{
        return $this->response;
    }
}
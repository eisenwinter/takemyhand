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
 * Something bad has happened,
 * a exception has been caught that couldn't be dealt with
 *
 */

namespace TakeMyHand\Events;


use TakeMyHand\Dispatcher\IMessage;

class ExceptionCaughtMessage implements IMessage
{

    private $sender_name;
    private $exception;

    public  function __construct(string $sender, \Exception $ex)
    {
        $this->sender_name = $sender;
        $this->exception = $ex;
    }

    function get_sender_name(): string
    {
        return $this->sender_name;
    }
}
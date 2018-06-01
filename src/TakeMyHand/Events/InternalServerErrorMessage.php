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
 * we need to emit a 500
 *
 */

namespace TakeMyHand\Events;


use TakeMyHand\Dispatcher\IMessage;

class InternalServerErrorMessage implements IMessage
{

    private $sender_name;
    private $details;

    public  function __construct(string $sender, ?string $details)
    {
        $this->sender_name = $sender;
        $this->details = $details;
    }

    function get_sender_name(): string
    {
        return $this->sender_name;
    }

    public function get_details() : ?string{
        return $this->details;
    }
}
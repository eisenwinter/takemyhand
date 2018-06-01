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
 * URI could not be matched
 *
 */


namespace TakeMyHand\Events;


use TakeMyHand\Dispatcher\IMessage;

class RouteNotFoundMessage implements IMessage
{
    private $route;

    public function __construct($route)
    {
        $this->route = $route;
    }

    public function get_sender_name() : string
    {
        return "IRouter";
    }

    public function get_route_name(){
        return $this->route;
    }


}
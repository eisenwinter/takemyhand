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
 * @category   Router
 * @package    TakeMyHand.Router
 * @author     Jan Caspar, <jan@subkonstrukt.at>
 * @copyright  2018 Jan Caspar
 * @license    https://opensource.org/licenses/MIT MIT
 * @version    v0.0.3
 * @since      v0.0.1
 *
 * The table is able to register new URIs linked to closures
 *
 */

namespace  TakeMyHand\Routing;

use Closure;

interface IRouteTable{
    /**
     * starts the register-process
     */
    function start_register();

    /**
     * @param string $verb http verb
     * @param string $pattern the uri pattern
     * @param Closure $exec the closure to call once the pattern is matched
     */
    function register(string $verb, string $pattern, Closure $exec);
    /**
     * ends the register-process
     */
    function end_register();
}
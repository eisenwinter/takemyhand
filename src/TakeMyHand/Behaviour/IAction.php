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
 * @category   Behaviour
 * @package    TakeMyHand.Behaviour
 * @author     Jan Caspar, <jan@subkonstrukt.at>
 * @copyright  2018 Jan Caspar
 * @license    https://opensource.org/licenses/MIT MIT
 * @version    v0.0.3
 * @since      v0.0.1
 *
 * action definition
 */

namespace TakeMyHand\Behaviour;


use Closure;

interface IAction
{
    function get(string $route, Closure $act);
    function post(string $route, Closure $act);
    function delete(string $route, Closure $act);
    function put(string $route, Closure $act);
    function patch(string $route, Closure $act);
}
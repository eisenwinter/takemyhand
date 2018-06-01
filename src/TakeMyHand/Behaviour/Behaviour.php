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
 * behaviour base class
 */

namespace TakeMyHand\Behaviour;


use TakeMyHand\Auth\IIdentity;

abstract class Behaviour
{
    //public becacuse it gets injected, sorreh I think this is a acceptable design choice :P
    /** @var IIdentity $auth */
    public $auth;

    protected function is_authenticated() : bool{
        return $this->auth != null && $this->auth->is_authenticated();
    }

    public abstract function on(IAction $action);
}
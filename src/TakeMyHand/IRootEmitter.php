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
 * @category   FrameworkBasics
 * @package    TakeMyHand
 * @author     Jan Caspar, <jan@subkonstrukt.at>
 * @copyright  2018 Jan Caspar
 * @license    https://opensource.org/licenses/MIT MIT
 * @version    v0.0.3
 * @since      v0.0.1
 *
 * This interface allows emitting messages trough the core
 *
 */


namespace TakeMyHand;


use TakeMyHand\Dispatcher\IMessage;

interface IRootEmitter
{
    /**
     * @param IMessage $message message to be dispatched trough the system
     * @return mixed
     */
    function emit(IMessage $message);
}
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
 * the action part of the behaviour
 */

namespace TakeMyHand\Behaviour;

use Closure;
use TakeMyHand\Events\ExceptionCaughtMessage;
use TakeMyHand\IRootEmitter;
use TakeMyHand\Routing\IRouteTable;

class Action implements IAction
{
    private $route_table;
    /** @var IRootEmitter $emitter*/
    private $emitter;

    public function __construct(IRouteTable $route_table, IRootEmitter $emitter)
    {
        $this->route_table = $route_table;
    }

    public function get(string $route, Closure $act)
    {
        try{
            $this->route_table->register('GET',$route,$act);
        }catch (\Exception $e){
            $this->emitter->emit(new ExceptionCaughtMessage('Action', $e));
        }

    }

    public function post(string $route, Closure $act)
    {
        try{
            $this->route_table->register('POST',$route,$act);
        }catch (\Exception $e){
            $this->emitter->emit(new ExceptionCaughtMessage('Action', $e));
        }
    }

    public function delete(string $route, Closure $act)
    {
        try{
            $this->route_table->register('DELETE',$route,$act);
        }catch (\Exception $e){
            $this->emitter->emit(new ExceptionCaughtMessage('Action', $e));
        }
    }

    public function put(string $route, Closure $act)
    {
        try{
            $this->route_table->register('PUT',$route,$act);
        }catch (\Exception $e){
            $this->emitter->emit(new ExceptionCaughtMessage('Action', $e));
        }
    }

    public function patch(string $route, Closure $act)
    {
        try{
            $this->route_table->register('PATCH',$route,$act);
        }catch (\Exception $e){
            $this->emitter->emit(new ExceptionCaughtMessage('Action', $e));
        }
    }
}
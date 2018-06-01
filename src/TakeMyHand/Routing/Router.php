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
 * Concrete implementation of the router, based on a routing trie
 *
 */

namespace  TakeMyHand\Routing;

use Closure;
use TakeMyHand\Events\ResponseReadyMessage;
use TakeMyHand\Events\RouteNotFoundMessage;
use TakeMyHand\IRootEmitter;
use TakeMyHand\Request;
use TakeMyHand\Routing\RoutingTrie\Match;
use TakeMyHand\Routing\RoutingTrie\Trie;
use TakeMyHand\Routing\RoutingTrie\TrieNodeFactory;

class Router implements IRouter {
    private $rebuild = false;
    private $routing_trie;
    private $routes = array();
    private $emitter;

    public function __construct(IRootEmitter $emitter)
    {
        $this->routing_trie = new Trie(new TrieNodeFactory());
        $this->emitter = $emitter;
    }

    public function resolve(Request $req)
    {
        $matches = $this->routing_trie->matches($req->get_method(),$req->get_uri());
        if(count($matches) === 0){
            $this->emitter->emit(new RouteNotFoundMessage($req->get_uri()));
            return;
        }

        usort($matches,function(Match $lhs,Match $rhs){
            return $lhs->get_meta()->get_score() < $rhs->get_meta()->get_score();
        });
        /** @var Match $match */
        $match = $matches[0];
        //emit to rendering
        $response = $this->routes[$match->get_meta()->get_to_call()]['exec']($req, $match->get_parameters());
        $this->emitter->emit(new ResponseReadyMessage('IRouter',$response));
    }

    public function start_register(){
        $this->rebuild = false;
    }

    public function register(string $verb, string $pattern, Closure $exec)
    {
        $verb = strtoupper($verb);
        array_push($this->routes,array(
                        "verb" => $verb,
                        "pattern" => $pattern,
                        "exec"  => $exec ));
        if($this->rebuild) {
            $this->build();
        }
    }

    public function end_register(){
        $this->rebuild = true;
        $this->build();
    }

    private function build(){
        $this->routing_trie->build($this->routes);
    }

}
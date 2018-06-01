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
 * Routing Trie: Nodes
 *
 */

namespace  TakeMyHand\Routing\RoutingTrie\Nodes;

use TakeMyHand\Routing\RoutingTrie\ITrieNodeFactory;
use TakeMyHand\Routing\RoutingTrie\Match;

class RootNode extends BaseNode {
    private $local = array();

    function __construct(ITrieNodeFactory  $factory) {
        parent::__construct(null, null, $factory);
    }

    public function get_routing_score() : int{
        return 0;
    }
    public function get_matches($segments, int $index = 0, $captured = array()){
        if(count($segments) == 0){
            return $this->to_result($captured,$this->local);
        }
        return $this->get_child_matches($segments,$index,$captured,$this->local);
    }

    public function match(string $segment) : Match {
        $match = new Match();
        $match->set_valid();
        return $match;
    }
}
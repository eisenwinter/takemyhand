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

class RegexNode extends BaseNode {
    private $regex;

    function __construct(BaseNode $parent, string $segment, ITrieNodeFactory  $factory) {
        parent::__construct($parent, $segment, $factory);
        $this->regex = $segment;
    }

    public function get_routing_score() : int{
        return 25;
    }

    public function match(string $segment) : Match{
        $match = new Match();
        if(preg_match($this->regex, $segment)){
            $match->set_valid();
            $match->add_parameter($this->regex, $segment);
        }
        return $match;
    }
}
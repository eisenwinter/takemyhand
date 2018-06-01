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

use Exception;
use TakeMyHand\Routing\RoutingTrie\ITrieNodeFactory;
use TakeMyHand\Routing\RoutingTrie\Match;

class CaptiveWithDefaultNode extends BaseNode {
    private $param;
    private $default;

    function __construct(BaseNode $parent, string $segment, ITrieNodeFactory  $factory) {
        parent::__construct($parent, $segment, $factory);
        $split = explode('?',substr($segment,1,strlen($segment)-2));
        if(count($split) != 2){
            throw new Exception("Invalid node segment '" . $segment . "'.");
        }
        $this->param = $split[0];
        $this->default = $split[1];
    }

    public function add(array $segments, int $index, int $score, int $node_count, string $method, int $to_call){
        parent::add($segments,$index,$score,$node_count,$method,$to_call);
        $this->get_parent()->set_additional_parameters($this->param,$this->default);
        $this->get_parent()->add($segments,$index,$score-$this->get_parent()->get_routing_score(),$node_count-1,$method,$to_call);
    }

    public function get_routing_score() : int{
        return 45;
    }

    public function match(string $segment) : Match{
        $match = new Match();
        $match->set_valid();
        $match->add_parameter($this->param, $segment);
        return $match;
    }
}
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
 * Routing Trie
 *
 */

namespace  TakeMyHand\Routing\RoutingTrie;

use TakeMyHand\Routing\RoutingTrie\Nodes\BaseNode;
use TakeMyHand\Routing\RoutingTrie\Nodes\CaptiveNode;
use TakeMyHand\Routing\RoutingTrie\Nodes\CaptiveWithDefaultNode;
use TakeMyHand\Routing\RoutingTrie\Nodes\OptionalCaptiveNode;
use TakeMyHand\Routing\RoutingTrie\Nodes\RegexNode;
use TakeMyHand\Routing\RoutingTrie\Nodes\RootNode;
use TakeMyHand\Routing\RoutingTrie\Nodes\StaticSegmentNode;
use TakeMyHand\Routing\RoutingTrie\Nodes\TypedCaptiveNode;

class TrieNodeFactory implements ITrieNodeFactory {
    function get_node_for_segment(?BaseNode $node, ?string $segment) {
        if($node == null){
            return new RootNode($this);
        }
        $segment_start = substr($segment, 0,1);
        $segment_end = substr($segment, -1);
        if($segment_start == '(' && $segment_end == ')'){
            return new RegexNode($node, $segment, $this);
        }
        if($segment_start == '{' && $segment_end == '}'){
            return $this->get_captive_node($node, $segment);
        }
        return new StaticSegmentNode($node, $segment, $this);

    }

    private function get_captive_node(BaseNode $parent, string $segment){
        // because !==false is supposed to have better perfomance (duh)
        //          --> https://stackoverflow.com/questions/2401478/why-is-faster-than-in-php/2401486#2401486
        // might wanna fix all the other ifs later on ...
        if (strpos($segment, ':') !== false){
            return new TypedCaptiveNode($parent, $segment, $this);
        }
        if(substr($segment, -2,2) === '?}'){
            return new  OptionalCaptiveNode($parent, $segment, $this);
        }
        if (strpos($segment, '?') !== false){
            return new CaptiveWithDefaultNode($parent, $segment, $this);
        }
        return new CaptiveNode($parent, $segment, $this);
    }

}
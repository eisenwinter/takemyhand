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
use TakeMyHand\Routing\RoutingTrie\RouteMetaData;

abstract class BaseNode {
    private $factory;
    private $parent;
    private $segment;
    private $children;
    private $meta;
    private $additional_parameters;

    // protected possible in php? look up
    function __construct(?BaseNode $parent, ?string $segment, ITrieNodeFactory  $factory) {
        $this->parent = $parent;
        $this->segment = $segment;
        $this->factory = $factory;
        $this->additional_parameters = array();
        $this->meta = array();
    }

    public function get_parent() : BaseNode{
        return $this->parent;
    }
    protected function set_parent(BaseNode $node){
        $this->parent = $node;
    }

    public function get_route_segment() : string{
        return $this->segment;
    }
    public function set_additional_parameters($key, $value){
        $this->additional_parameters[$key] = $value;
    }
    protected function set_route_segment(string $segment){
        $this->segment = $segment;
    }

    public function get_children() : array {
        return $this->children;
    }

    protected function set_children(array $children){
        $this->children = $children;
    }

    protected function end_of_segments($segments, int $index){
        return count($segments) - 1 === $index;
    }

    protected function to_result($params, $captures) : array{
        if(count($this->meta) == 0){
            return array();
        }
        $p = $params;
        if(count($this->additional_parameters) > 0){
            foreach($this->additional_parameters as $key => $value){
                if(!isset($p[$key])){
                    $p[$key] = $value;
                }
            }
        }
        if(count($captures) > 0){
            foreach($captures as $key => $value){
                $p[$key] = $value;
            }
        }

        $ret = array();
        foreach($this->meta as $meta){
            $m = new Match();
            $m->set_valid();
            $m->set_parameters($p);
            $m->set_meta($meta);
            array_push($ret,$m);
        }
        return $ret;
    }


    public function add(array $segments, int $index, int $score, int $node_count, string $method, int $to_call){
        if(!$this->end_of_segments($segments,$index)){
            $node_count++;
            $index++;
            /** @var BaseNode $child */
	    $child = null;
	    if($this->children != null && array_key_exists($segments[$index],$this->children)){
		 $child = $this->children[$segments[$index]];
	    }else{
		$child = $this->factory->get_node_for_segment($this, $segments[$index]);
                $this->children[$segments[$index]] = $child;
	    }
                   
            $child->add($segments, $index, $score + $this->get_routing_score(),$node_count, $method, $to_call);
        }else{
            array_push($this->meta, new RouteMetaData($method, $score, count($segments),$to_call));
        }

    }

    public function get_matches($segments, int $index = 0, $captured = array()){
        $match = $this->match($segments[$index]);
        if(!$match->is_valid()){
            return $match;
        }
        if($this->end_of_segments($segments, $index)){
            return $this->to_result($captured, $match->get_parameters());
        }
        $index++;
        return $this->get_child_matches($segments, $index, $captured, $match->get_parameters());

    }

    protected function get_child_matches($segments, int $index, $captured, $locals) : array{
        $p = $captured;
        if(count($locals) > 0 || count($this->additional_parameters) > 0){
            foreach($locals as $key => $value){
                $p[$key] = $value;
            }
            foreach($this->additional_parameters as $key => $value){
                $p[$key] = $value;
            }
        }
        $matches = array();
		if($this->children){
			foreach($this->children as $child){
				/** @var BaseNode $child */
				foreach($child->get_matches($segments, $index, $p) as $m){
					array_push($matches, $m);
				}
			}
		}

        return $matches;
    }

    public abstract function get_routing_score() : int;    
    public abstract function match(string $segment) : Match;



}
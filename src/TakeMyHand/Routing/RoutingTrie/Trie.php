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
 * @version    v0.0.4
 * @since      v0.0.1
 *
 * Routing Trie
 * 0.0.4 - fixing up for apache to work as well
 *
 */


namespace  TakeMyHand\Routing\RoutingTrie;

class Trie {
    private $tries = array();
    const ROUTE_SEPARATOR = '/';
    private $factory;

    public function __construct(ITrieNodeFactory $factory){
        $this->factory = $factory;
    }

    private function split_route($pattern) : array{
        return preg_split('/\\'.self::ROUTE_SEPARATOR.'/', $pattern, -1, PREG_SPLIT_NO_EMPTY);
    }

    public function build($route_data){
        foreach($route_data as $key => $route){
			$method_trie = null;
			if(!array_key_exists($route['verb'], $this->tries)){
				$method_trie = $this->factory->get_node_for_segment(null,null);
                $this->tries[$route['verb']] = $method_trie;
			}else{
				$method_trie = $this->tries[$route['verb']];
			}
            
            $segments = $this->split_route($route['pattern']);
            $method_trie->add($segments, -1,0,0, $route['verb'],$key);
        }
    }

    public function matches(string $method, string $path){
        if(!array_key_exists($method,$this->tries) || !isset($this->tries[$method])){
            return null;
        }
        $node = $this->tries[$method];
        return $node->get_matches($this->split_route($path),0,array());

    }
    
}
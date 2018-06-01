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

class Match{
    private $parameters = array();
    private $valid = false;
    private $meta ;

    public function __construct(){

    }

    public function set_valid(){
        $this->valid = true;
    }

    public function is_valid() : bool{
        return $this->valid;
    }

    public function add_parameter(string $param, string $segment){
        $this->parameters[$param] = $segment;
    }

    public function get_parameters() : array {
        return $this->parameters;
    }

    public function set_parameters(array $params){
        $this->parameters = $params;
    }

    public function get_meta() : RouteMetaData {
        return $this->meta;
    }

    public function set_meta(RouteMetaData $meta){
        $this->meta = $meta;
    }


}
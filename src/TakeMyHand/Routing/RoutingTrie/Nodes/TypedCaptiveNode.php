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

class TypedCaptiveNode extends BaseNode{
    private $param;
    private $type_constraint;
    private $validate;

    function __construct(BaseNode $parent, string $segment, ITrieNodeFactory  $factory) {
        parent::__construct($parent, $segment, $factory);
        $split = explode(':',substr($segment,1,strlen($segment)-2));

        if(count($split) != 2){
            throw new Exception("Invalid node segment '" . $segment . "'.");
        }
        $this->param = $split[0];
        $this->type_constraint = $split[1];
        switch($this->type_constraint){
            case "int":
                $this->validate = function($type) : bool {
                    return ctype_digit($type);
                };
                break;
            case "number":
                $this->validate = function($type) : bool {
                    return is_numeric($type);
                };
                break;
            case "date":
                $this->validate = function($type) : bool {
                    return strtotime($type) > -1;
                };
                break;
            case "version":
                $this->validate = function($type) : bool {
                    return preg_match('/(v|)[0-9]*\.[0-9]*(\.[0-9]*|)/',$type) == 1;
                };
                break;
            default:
                throw  new Exception("Unknown type constraint in route " . $segment . ". Valid are int, number, date and version");
        }
    }

    public function get_routing_score(): int
    {
        return 50;
    }


    public function match(string $segment): Match
    {
        $match = new Match();
        if(($this->validate)($segment)){
            $match->set_valid();
            $match->add_parameter($this->param,$segment);
        }
        return $match;
    }
}
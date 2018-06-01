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


namespace TakeMyHand\Routing\RoutingTrie;


class RouteMetaData
{
    private $to_call;
    private $method;
    private $segment_count;
    private $score;

    public function __construct(string $method, int $score, int $segments, int $to_call)
    {
        $this->to_call = $to_call;
        $this->method = $method;
        $this->score = $score;
        $this->segment_count = $segments;
    }

    public function get_to_call() : int{
        return $this->to_call;
    }

    public function get_method(){
        return $this->method;
    }

    public function get_segment_count(){
        return $this->segment_count;
    }

    public function get_score(){
        return $this->score;
    }
}